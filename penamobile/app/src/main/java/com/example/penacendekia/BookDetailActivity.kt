package com.example.penacendekia

import android.content.Intent
import android.os.Build
import android.os.Bundle
import android.widget.ImageView
import android.widget.TextView
import androidx.activity.addCallback
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
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

class BookDetailActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView
    private var isFromShelf: Boolean = true
    private var currentBook: Book? = null

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
                v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
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
            PageTransition.slideForward(this, Intent(this, ReaderActivity::class.java).putExtra(ReaderActivity.EXTRA_BOOK, book))
        }

        // Navigate to PaymentActivity or Reader when clicking buy / action
        findViewById<android.view.View>(R.id.btnActionBuy)?.setOnClickListener {
            val book = currentBook ?: passedBook
            if (book?.type.equals("ebook", ignoreCase = true) && (book?.is_free == true || (book?.price ?: 0.0) <= 0.0)) {
                PageTransition.slideForward(this, Intent(this, ReaderActivity::class.java).putExtra(ReaderActivity.EXTRA_BOOK, book))
            } else {
                val intent = Intent(this, PaymentActivity::class.java).apply {
                    putExtra(PaymentActivity.EXTRA_BOOK, book)
                }
                PageTransition.slideForward(this, intent)
            }
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

        // If we have an ID, fetch latest details from API
        if (passedBook == null && bookId > 0) {
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

        val isEbook = displayBook.type.equals("ebook", ignoreCase = true)
        val priceText = if (displayBook.is_free || displayBook.price <= 0.0) {
            displayBook.formatted_price ?: "Gratis"
        } else {
            displayBook.formatted_price ?: "Rp ${displayBook.price}"
        }
        findViewById<TextView>(R.id.tvBookPriceDetail)?.text = priceText
        findViewById<TextView>(R.id.tvBtnActionBuyDetail)?.text =
            if (isEbook && (displayBook.is_free || displayBook.price <= 0.0)) "Baca E-Book Gratis" else "Beli E-Book · $priceText"
        updateFavoriteButton(BookActivityStore.isFavorite(this, displayBook))

        val ivCover = findViewById<ImageView>(R.id.ivBookCoverDetail)
        if (ivCover != null) {
            val formattedUrl = RetrofitClient.getFormattedImageUrl(displayBook.cover_url)
            val localCover = BookCoverResolver.localCover(displayBook)
            val fallbackCover = BookCoverResolver.placeholder(displayBook)
            try {
                Glide.with(this)
                    .load(formattedUrl ?: localCover ?: fallbackCover)
                    .placeholder(fallbackCover)
                    .error(fallbackCover)
                    .into(ivCover)
            } catch (e: Exception) {
                e.printStackTrace()
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
