package com.example.penacendekia

import android.content.Intent
import android.content.res.ColorStateList
import android.graphics.Color
import android.os.Build
import android.os.Bundle
import android.widget.ImageView
import android.widget.TextView
import androidx.activity.addCallback
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.bumptech.glide.Glide
import com.example.penacendekia.data.api.RetrofitClient
import com.example.penacendekia.data.model.ApiResponse
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.repository.BookCatalogRepository
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.bottomnavigation.BottomNavigationView
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.text.NumberFormat
import java.util.Locale

class BookDetailActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView
    private var isFromShelf: Boolean = true
    private var currentBook: Book? = null
    private var selectedOptionBuy: Boolean = true

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_book_detail)

        isFromShelf = intent.getBooleanExtra("FROM_SHELF", true)

        val passedBook: Book? = try {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
                intent.getSerializableExtra("BOOK_OBJECT", Book::class.java)
            } else {
                @Suppress("DEPRECATION")
                intent.getSerializableExtra("BOOK_OBJECT") as? Book
            }
        } catch (e: Exception) {
            null
        }

        val bookId = intent.getIntExtra("BOOK_ID", passedBook?.id ?: -1)

        val toolbar = findViewById<android.view.View>(R.id.toolbarDetail)
        if (toolbar != null) {
            ViewCompat.setOnApplyWindowInsetsListener(toolbar) { v, insets ->
                val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
                val lp = v.layoutParams as? android.view.ViewGroup.MarginLayoutParams
                if (lp != null && lp.topMargin != systemBars.top) {
                    lp.topMargin = systemBars.top
                    v.layoutParams = lp
                }
                insets
            }
        }

        // Back button action with smooth slide transition
        findViewById<ImageView>(R.id.btnBackDetail)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@BookDetailActivity)
        }

        findViewById<ImageView>(R.id.btnFavoriteDetail)?.setOnClickListener {
            val book = currentBook ?: Book(
                id = bookId,
                title = findViewById<TextView>(R.id.tvBookTitleDetail)?.text?.toString().orEmpty(),
                author = findViewById<TextView>(R.id.tvBookAuthorDetail)?.text?.toString().orEmpty()
            )
            val isFavorite = BookActivityStore.toggleFavorite(this, book)
            updateFavoriteButton(isFavorite)
            val message = if (isFavorite) "Ditambahkan ke favorit." else "Dihapus dari favorit."
            android.widget.Toast.makeText(this, message, android.widget.Toast.LENGTH_SHORT).show()
        }

        // Navigate to ReaderActivity when clicking action read
        findViewById<android.view.View>(R.id.btnActionRead)?.setOnClickListener {
            val book = currentBook ?: passedBook ?: Book(
                id = bookId,
                title = findViewById<TextView>(R.id.tvBookTitleDetail)?.text?.toString().orEmpty(),
                author = findViewById<TextView>(R.id.tvBookAuthorDetail)?.text?.toString().orEmpty()
            )
            val isFreeBook = book.is_free || book.price <= 0.0

            // Cegah bypass jika tombol sedang dimatikan (mode Beli E-Book aktif)
            if (selectedOptionBuy && !isFreeBook) {
                android.widget.Toast.makeText(
                    this,
                    "Tombol nonaktif. Silakan pilih opsi 'Baca Buku (Gratis 7 hari)' di atas untuk membaca gratis.",
                    android.widget.Toast.LENGTH_SHORT
                ).show()
                return@setOnClickListener
            }

            val targetPage = (BookActivityStore.getPageBookmark(this, book)?.page
                ?: BookActivityStore.getActiveSessions(this).find { BookActivityStore.bookKey(it.book) == BookActivityStore.bookKey(book) }?.currentPage
                ?: 1).coerceAtLeast(1)
            PageTransition.slideForward(
                this,
                Intent(this, ReaderActivity::class.java)
                    .putExtra(ReaderActivity.EXTRA_BOOK, book)
                    .putExtra(ReaderActivity.EXTRA_PAGE, targetPage)
            )
        }

        // Navigate to PaymentActivity or Reader when clicking buy / action
        findViewById<android.view.View>(R.id.btnActionBuy)?.setOnClickListener {
            val book = currentBook ?: passedBook
            val isFreeBook = book?.is_free == true || (book?.price ?: 0.0) <= 0.0
            if (isFreeBook) {
                PageTransition.slideForward(this, Intent(this, ReaderActivity::class.java).putExtra(ReaderActivity.EXTRA_BOOK, book))
                return@setOnClickListener
            }

            // Cegah aksi beli jika tombol beli sedang dimatikan (mode Baca Gratis aktif)
            if (!selectedOptionBuy) {
                android.widget.Toast.makeText(
                    this,
                    "Tombol nonaktif. Silakan pilih opsi 'Beli buku digital' di atas untuk membeli e-book.",
                    android.widget.Toast.LENGTH_SHORT
                ).show()
                return@setOnClickListener
            }

            val intent = Intent(this, PaymentActivity::class.java).apply {
                putExtra(PaymentActivity.EXTRA_BOOK, book)
            }
            PageTransition.slideForward(this, intent)
        }

        // Klik ulasan / rating membuka ReviewActivity
        findViewById<android.view.View>(R.id.tvBookRatingDetail)?.setOnClickListener {
            val book = currentBook ?: passedBook
            val intent = Intent(this, ReviewActivity::class.java).apply {
                putExtra(ReaderActivity.EXTRA_BOOK, book)
            }
            PageTransition.slideForward(this, intent)
        }

        // Bind initially if passed via intent or from local repository
        val initialBook = passedBook
            ?: BookCatalogRepository.findBook(bookId.toString())
            ?: BookCatalogRepository.ALL_11_BOOKS[0]
        bindBookData(initialBook)

        // Always fetch latest details from API if bookId > 0 so price & metadata are live from database
        if (bookId > 0) {
            fetchBookDetail(bookId)
        }

        bottomNav = findViewById(R.id.bottomNavigationDetail)

        // Set active item visually without triggering navigation listener
        val targetNavId = if (isFromShelf) R.id.nav_rak else R.id.nav_katalog
        bottomNav.menu.findItem(targetNavId)?.isChecked = true

        // Bottom Navigation Listener with smooth tab transition
        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_beranda -> {
                    PageTransition.switchTab(this, MainActivity::class.java)
                    true
                }
                R.id.nav_katalog -> {
                    PageTransition.switchTab(this, CatalogActivity::class.java)
                    true
                }
                R.id.nav_rak -> {
                    PageTransition.switchTab(this, ShelfActivity::class.java)
                    true
                }
                R.id.nav_profil -> {
                    PageTransition.switchTab(this, ProfileActivity::class.java)
                    true
                }
                else -> false
            }
        }
    }

    private fun fetchBookDetail(id: Int) {
        RetrofitClient.apiService.getBookDetail(id).enqueue(object : Callback<ApiResponse<Book>> {
            override fun onResponse(call: Call<ApiResponse<Book>>, response: Response<ApiResponse<Book>>) {
                if (isFinishing || isDestroyed) return
                if (response.isSuccessful) {
                    val book = response.body()?.data
                    if (book != null) {
                        bindBookData(book)
                    }
                }
            }

            override fun onFailure(call: Call<ApiResponse<Book>>, t: Throwable) {
                // Gunakan data lengkap dari repository lokal jika offline
                val fallback = BookCatalogRepository.findBook(id.toString())
                if (fallback != null) {
                    bindBookData(fallback)
                }
            }
        })
    }

    private fun bindBookData(book: Book) {
        if (isFinishing || isDestroyed) return

        val displayBook = withKnownBookDetails(book)
        currentBook = displayBook
        findViewById<TextView>(R.id.tvBookTitleDetail)?.text = displayBook.title
        findViewById<TextView>(R.id.tvBookAuthorDetail)?.text =
            listOfNotNull(displayBook.author.takeIf { it.isNotBlank() }, displayBook.category?.takeIf { it.isNotBlank() })
                .joinToString(" · ")
        findViewById<TextView>(R.id.tvBookRatingDetail)?.text = "★ ${displayBook.rating} · ${displayBook.rating_count} ulasan"
        findViewById<TextView>(R.id.tvSessionDurationDetail)?.text = "7 Hari"

        findViewById<TextView>(R.id.tvBookEditorDetail)?.apply {
            val editor = displayBook.editor
            if (editor.isNullOrBlank()) {
                visibility = android.view.View.GONE
            } else {
                text = "Editor: $editor"
                visibility = android.view.View.VISIBLE
            }
        }

        findViewById<TextView>(R.id.tvBookSynopsisDetail)?.text = displayBook.synopsis.orEmpty()

        val publisherAndYear = listOfNotNull(
            displayBook.publisher?.takeIf { it.isNotBlank() },
            displayBook.year?.takeIf { it.isNotBlank() }
        ).joinToString(" · ")
        findViewById<TextView>(R.id.tvBookPublisherDetail)?.apply {
            text = publisherAndYear
            visibility = if (publisherAndYear.isBlank()) android.view.View.GONE else android.view.View.VISIBLE
        }

        bindOptionalMetadata(R.id.tvBookPagesDetail, displayBook.pages?.let { "Tebal: ${formatPages(it)}" })
        bindOptionalMetadata(R.id.tvBookSizeDetail, displayBook.size?.let { "Ukuran: $it" })
        bindOptionalMetadata(R.id.tvBookIsbnDetail, displayBook.isbn?.let { "ISBN: $it" })

        val isFree = displayBook.is_free || displayBook.price <= 0.0
        val priceText = if (isFree) {
            "Gratis"
        } else if (!displayBook.formatted_price.isNullOrBlank() && !displayBook.formatted_price.equals("Gratis", ignoreCase = true)) {
            displayBook.formatted_price
        } else {
            "Rp " + NumberFormat.getIntegerInstance(Locale.forLanguageTag("id-ID")).format(displayBook.price.toLong())
        }
        findViewById<TextView>(R.id.tvBookPriceDetail)?.text = priceText

        updateSelectionUI(selectedOptionBuy, isFree, priceText)

        findViewById<com.google.android.material.card.MaterialCardView>(R.id.cardOptionFree)?.setOnClickListener {
            updateSelectionUI(false, isFree, priceText)
        }
        findViewById<com.google.android.material.card.MaterialCardView>(R.id.cardOptionBuy)?.setOnClickListener {
            updateSelectionUI(true, isFree, priceText)
        }

        updateFavoriteButton(BookActivityStore.isFavorite(this, displayBook))

        val ivCover = findViewById<ImageView>(R.id.ivBookCoverDetail)
        if (ivCover != null) {
            BookCoverResolver.loadCover(ivCover, displayBook)
        }
    }

    private fun updateSelectionUI(isBuy: Boolean, isFreeBook: Boolean, priceText: String) {
        selectedOptionBuy = isBuy
        val cardOptionFree = findViewById<com.google.android.material.card.MaterialCardView>(R.id.cardOptionFree)
        val cardOptionBuy = findViewById<com.google.android.material.card.MaterialCardView>(R.id.cardOptionBuy)
        val ivOptionFreeCheck = findViewById<ImageView>(R.id.ivOptionFreeCheck)
        val ivOptionBuyCheck = findViewById<ImageView>(R.id.ivOptionBuyCheck)
        val tvOptionFreeBadge = findViewById<TextView>(R.id.tvOptionFreeBadge)
        val tvOptionBuyBadge = findViewById<TextView>(R.id.tvOptionBuyBadge)
        val tvOptionBuyTitle = findViewById<TextView>(R.id.tvOptionBuyTitle)

        val btnActionRead = findViewById<com.google.android.material.card.MaterialCardView>(R.id.btnActionRead)
        val tvBtnActionRead = findViewById<TextView>(R.id.tvBtnActionReadDetail)
        val ivBtnActionRead = findViewById<ImageView>(R.id.ivBtnActionReadDetail)

        val btnActionBuy = findViewById<com.google.android.material.card.MaterialCardView>(R.id.btnActionBuy)
        val tvBtnActionBuy = findViewById<TextView>(R.id.tvBtnActionBuyDetail)
        val ivBtnActionBuy = findViewById<ImageView>(R.id.ivBtnActionBuyDetail)

        val colorPrimary = ContextCompat.getColor(this, R.color.primary_green)
        val colorOutline = ContextCompat.getColor(this, R.color.outline_green)
        val colorBgSelected = ContextCompat.getColor(this, R.color.bg_light_green)
        val colorDivider = ContextCompat.getColor(this, R.color.divider_color)
        val colorCardBg = ContextCompat.getColor(this, R.color.card_bg)
        val colorTextDark = ContextCompat.getColor(this, R.color.text_dark)
        val colorTextMuted = ContextCompat.getColor(this, R.color.text_muted)

        val colorDisabledBgRead = Color.parseColor("#F5F5F5")
        val colorDisabledBgBuy = Color.parseColor("#E5E7EB")
        val colorDisabledBorder = Color.parseColor("#D1D5DB")
        val colorDisabledText = Color.parseColor("#9CA3AF")

        val strokeWidthSelected = (2 * resources.displayMetrics.density).toInt()
        val strokeWidthNormal = (1 * resources.displayMetrics.density).toInt()

        if (isFreeBook) {
            tvOptionBuyTitle?.text = "Akses Penuh E-Book"
            cardOptionBuy?.strokeColor = colorOutline
            cardOptionBuy?.strokeWidth = strokeWidthSelected
            cardOptionBuy?.setCardBackgroundColor(colorBgSelected)
            tvOptionBuyBadge?.text = "DIPILIH"
            tvOptionBuyBadge?.setTextColor(colorPrimary)
            ivOptionBuyCheck?.setImageResource(R.drawable.ic_check_circle)
            ivOptionBuyCheck?.imageTintList = ColorStateList.valueOf(colorPrimary)

            cardOptionFree?.strokeColor = colorDivider
            cardOptionFree?.strokeWidth = strokeWidthNormal
            cardOptionFree?.setCardBackgroundColor(colorCardBg)
            tvOptionFreeBadge?.text = "Akses berdurasi"
            tvOptionFreeBadge?.setTextColor(colorTextMuted)
            ivOptionFreeCheck?.setImageResource(R.drawable.ic_clock)
            ivOptionFreeCheck?.imageTintList = ColorStateList.valueOf(colorTextDark)

            btnActionRead?.visibility = android.view.View.GONE
            btnActionBuy?.visibility = android.view.View.VISIBLE
            btnActionBuy?.alpha = 1.0f
            btnActionBuy?.setCardBackgroundColor(colorOutline)
            btnActionBuy?.strokeWidth = 0
            tvBtnActionBuy?.text = "Baca E-Book Gratis"
            tvBtnActionBuy?.setTextColor(Color.WHITE)
            ivBtnActionBuy?.imageTintList = ColorStateList.valueOf(Color.WHITE)
        } else {
            tvOptionBuyTitle?.text = "Beli buku digital"
            btnActionRead?.visibility = android.view.View.VISIBLE
            btnActionBuy?.visibility = android.view.View.VISIBLE

            if (isBuy) {
                // 1. Opsi "Beli buku digital" DIPILIH
                cardOptionBuy?.strokeColor = colorOutline
                cardOptionBuy?.strokeWidth = strokeWidthSelected
                cardOptionBuy?.setCardBackgroundColor(colorBgSelected)
                tvOptionBuyBadge?.text = "DIPILIH"
                tvOptionBuyBadge?.setTextColor(colorPrimary)
                ivOptionBuyCheck?.setImageResource(R.drawable.ic_check_circle)
                ivOptionBuyCheck?.imageTintList = ColorStateList.valueOf(colorPrimary)

                cardOptionFree?.strokeColor = colorDivider
                cardOptionFree?.strokeWidth = strokeWidthNormal
                cardOptionFree?.setCardBackgroundColor(colorCardBg)
                tvOptionFreeBadge?.text = "Akses berdurasi"
                tvOptionFreeBadge?.setTextColor(colorTextMuted)
                ivOptionFreeCheck?.setImageResource(R.drawable.ic_clock)
                ivOptionFreeCheck?.imageTintList = ColorStateList.valueOf(colorTextDark)

                // Tombol "Beli E-Book" MENYALA (Aktif)
                btnActionBuy?.alpha = 1.0f
                btnActionBuy?.setCardBackgroundColor(colorOutline)
                btnActionBuy?.strokeWidth = 0
                tvBtnActionBuy?.text = "Beli E-Book · $priceText"
                tvBtnActionBuy?.setTextColor(Color.WHITE)
                ivBtnActionBuy?.imageTintList = ColorStateList.valueOf(Color.WHITE)

                // Tombol "Baca Buku · Gratis 7 hari" DIMATIKAN (Nonaktif)
                btnActionRead?.alpha = 0.45f
                btnActionRead?.setCardBackgroundColor(colorDisabledBgRead)
                btnActionRead?.strokeColor = colorDisabledBorder
                btnActionRead?.strokeWidth = strokeWidthNormal
                tvBtnActionRead?.text = "Baca Buku · Gratis 7 hari"
                tvBtnActionRead?.setTextColor(colorDisabledText)
                ivBtnActionRead?.imageTintList = ColorStateList.valueOf(colorDisabledText)
            } else {
                // 2. Opsi "Baca Buku (Gratis 7 hari)" DIPILIH
                cardOptionFree?.strokeColor = colorOutline
                cardOptionFree?.strokeWidth = strokeWidthSelected
                cardOptionFree?.setCardBackgroundColor(colorBgSelected)
                tvOptionFreeBadge?.text = "DIPILIH"
                tvOptionFreeBadge?.setTextColor(colorPrimary)
                ivOptionFreeCheck?.setImageResource(R.drawable.ic_check_circle)
                ivOptionFreeCheck?.imageTintList = ColorStateList.valueOf(colorPrimary)

                cardOptionBuy?.strokeColor = colorDivider
                cardOptionBuy?.strokeWidth = strokeWidthNormal
                cardOptionBuy?.setCardBackgroundColor(colorCardBg)
                tvOptionBuyBadge?.text = "Akses permanen"
                tvOptionBuyBadge?.setTextColor(colorTextMuted)
                ivOptionBuyCheck?.setImageResource(R.drawable.ic_book_open)
                ivOptionBuyCheck?.imageTintList = ColorStateList.valueOf(colorTextMuted)

                // Tombol "Baca Buku · Gratis 7 hari" MENYALA (Aktif)
                btnActionRead?.alpha = 1.0f
                btnActionRead?.setCardBackgroundColor(Color.WHITE)
                btnActionRead?.strokeColor = colorOutline
                btnActionRead?.strokeWidth = (1.5 * resources.displayMetrics.density).toInt()
                tvBtnActionRead?.text = "Baca Buku · Gratis 7 hari"
                tvBtnActionRead?.setTextColor(colorOutline)
                ivBtnActionRead?.imageTintList = ColorStateList.valueOf(colorOutline)

                // Tombol "Beli E-Book" DIMATIKAN (Nonaktif)
                btnActionBuy?.alpha = 0.45f
                btnActionBuy?.setCardBackgroundColor(colorDisabledBgBuy)
                btnActionBuy?.strokeWidth = 0
                tvBtnActionBuy?.text = "Beli E-Book · $priceText"
                tvBtnActionBuy?.setTextColor(colorDisabledText)
                ivBtnActionBuy?.imageTintList = ColorStateList.valueOf(colorDisabledText)
            }
        }
    }

    private fun withKnownBookDetails(book: Book): Book {
        return BookCatalogRepository.enrichBook(book)
    }

    private fun bindOptionalMetadata(viewId: Int, value: String?) {
        findViewById<TextView>(viewId)?.apply {
            text = value.orEmpty()
            visibility = if (value.isNullOrBlank()) android.view.View.GONE else android.view.View.VISIBLE
        }
    }

    private fun updateFavoriteButton(isFavorite: Boolean) {
        findViewById<ImageView>(R.id.btnFavoriteDetail)?.apply {
            setImageResource(if (isFavorite) R.drawable.ic_heart else R.drawable.ic_heart_outline)
            contentDescription = if (isFavorite) "Hapus dari favorit" else "Tambahkan ke favorit"
        }
        findViewById<android.view.View>(R.id.layoutInFavoriteStatus)?.visibility =
            if (isFavorite) android.view.View.VISIBLE else android.view.View.GONE
    }

    private fun formatPages(pages: String): String =
        if (pages.contains("halaman", ignoreCase = true) || pages.contains("hlm", ignoreCase = true)) {
            pages
        } else {
            "$pages halaman"
        }

    private inline fun String?.ifNullOrBlank(defaultValue: () -> String): String =
        if (isNullOrBlank()) defaultValue() else this

}
