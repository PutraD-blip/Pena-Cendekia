package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import androidx.activity.addCallback
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.res.ResourcesCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.bumptech.glide.Glide
import com.example.penacendekia.data.api.RetrofitClient
import com.example.penacendekia.data.model.ApiResponse
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.repository.BookCatalogRepository
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.card.MaterialCardView
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class HistoryFavoriteActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView
    private var allBooks: List<Book> = emptyList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_history_favorite)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.toolbarHistory)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        findViewById<ImageView>(R.id.btnBackHistory)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@HistoryFavoriteActivity)
        }
        findViewById<ImageView>(R.id.btnSearchHistory)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, SearchActivity::class.java))
        }

        val historyBooks = listOf(
            BookCatalogRepository.findBook("Administrasi Rekam Medis") ?: Book(id = -1, title = "Administrasi Rekam Medis", author = "Dr. Aries Wahyuningsih", category = "E-Book · Kesehatan"),
            BookCatalogRepository.findBook("Penyakit Cacing") ?: Book(
                id = -1,
                title = "Penyakit Cacing, Protozoa Dan Gizi Pada Peternakan Unggas Sebagai Dasar Kajian Pada Embrio Hewan",
                author = "Maslichah Mafruchati",
                category = "E-Book · Kedokteran Hewan"
            )
        )
        findViewById<TextView>(R.id.tvHistoryTitle1)?.text = historyBooks[0].title
        findViewById<TextView>(R.id.tvHistoryAuthor1)?.text = historyBooks[0].author
        findViewById<TextView>(R.id.tvHistoryTitle2)?.text = historyBooks[1].title
        findViewById<TextView>(R.id.tvHistoryAuthor2)?.text = historyBooks[1].author
        findViewById<ImageView>(R.id.ivHistoryCover1)?.setImageResource(R.drawable.img_administrasi_cover)
        findViewById<ImageView>(R.id.ivHistoryCover2)?.setImageResource(R.drawable.img_penyakit_cacing)
        findViewById<ImageView>(R.id.ivHistoryCover1)?.contentDescription = "Sampul ${historyBooks[0].title}"
        findViewById<ImageView>(R.id.ivHistoryCover2)?.contentDescription = "Sampul ${historyBooks[1].title}"
        findViewById<TextView>(R.id.tvFinishedBooksCount)?.text = historyBooks.size.toString()
        findViewById<TextView>(R.id.tvHistoryHeaderCount)?.text = "Lihat semua · ${historyBooks.size}"
        findViewById<LinearLayout>(R.id.layoutHistoryBook1)?.setOnClickListener { openBookDetail(historyBooks[0]) }
        findViewById<LinearLayout>(R.id.layoutHistoryBook2)?.setOnClickListener { openBookDetail(historyBooks[1]) }
        findViewById<LinearLayout>(R.id.actionWriteReview1)?.setOnClickListener { openReview(historyBooks[0]) }
        findViewById<LinearLayout>(R.id.actionWriteReview2)?.setOnClickListener { openReview(historyBooks[1]) }
        findViewById<LinearLayout>(R.id.btnReadAgain1)?.setOnClickListener { openBookDetail(historyBooks[0]) }
        findViewById<LinearLayout>(R.id.btnReadAgain2)?.setOnClickListener { openBookDetail(historyBooks[1]) }

        bottomNav = findViewById(R.id.bottomNavigationHistory)
        bottomNav.menu.findItem(R.id.nav_rak)?.isChecked = true

        // Tulis ulasan actions -> Opens ReviewActivity
        findViewById<LinearLayout>(R.id.actionWriteReview1)?.setOnClickListener {
            openReview(allBooks.getOrNull(0))
        }
        findViewById<LinearLayout>(R.id.actionWriteReview2)?.setOnClickListener {
            openReview(allBooks.getOrNull(1))
        }

        // Default "Baca Lagi?" actions open ReaderActivity
        findViewById<LinearLayout>(R.id.btnReadAgain1)?.setOnClickListener {
            openReader(allBooks.getOrNull(0))
        }
        findViewById<LinearLayout>(R.id.btnReadAgain2)?.setOnClickListener {
            openReader(allBooks.getOrNull(1))
        }

        bottomNav = findViewById(R.id.bottomNavigationHistory)

        // Set Rak tab checked without triggering listener
        bottomNav.menu.findItem(R.id.nav_rak)?.isChecked = true

        // Bottom Navigation Listener
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
        // Fetch live database books
        loadBooksFromApi()
    }

    private fun loadBooksFromApi() {
        RetrofitClient.apiService.getBooks().enqueue(object : Callback<ApiResponse<List<Book>>> {
            override fun onResponse(call: Call<ApiResponse<List<Book>>>, response: Response<ApiResponse<List<Book>>>) {
                if (response.isSuccessful) {
                    val books = response.body()?.data
                    if (!books.isNullOrEmpty()) {
                        allBooks = books
                        bindBooks(books)
                    }
                }
            }

            override fun onFailure(call: Call<ApiResponse<List<Book>>>, t: Throwable) {
                // Keep default real book views
            }
        })
    }

    private fun bindBooks(books: List<Book>) {
        val b1 = books.find { it.title.contains("Administrasi", ignoreCase = true) } ?: books[0]
        val b2 = books.find { it.title.contains("Kebinekaan", ignoreCase = true) } ?: (if (books.size > 1) books[1] else books[0])
        val b3 = books.find { it.title.contains("Digitalisasi", ignoreCase = true) } ?: (if (books.size > 2) books[2] else books[0])
        val b4 = books.find { it.title.contains("Munakahat", ignoreCase = true) } ?: (if (books.size > 3) books[3] else books[0])

        findViewById<TextView>(R.id.tvHistoryTitle1)?.text = b1.title
        findViewById<TextView>(R.id.tvHistoryTitle2)?.text = b2.title
        findViewById<TextView>(R.id.tvFavTitle1)?.text = b3.title
        findViewById<TextView>(R.id.tvFavTitle2)?.text = b4.title

        findViewById<LinearLayout>(R.id.actionWriteReview1)?.setOnClickListener { openReview(b1) }
        findViewById<LinearLayout>(R.id.actionWriteReview2)?.setOnClickListener { openReview(b2) }

        findViewById<LinearLayout>(R.id.btnReadAgain1)?.setOnClickListener { openReader(b3) }
        findViewById<LinearLayout>(R.id.btnReadAgain2)?.setOnClickListener { openReader(b4) }
    }

    private fun openReview(book: Book?) {
        val intent = Intent(this, ReviewActivity::class.java)
        if (book != null) {
            intent.putExtra(ReaderActivity.EXTRA_BOOK, BookCatalogRepository.enrichBook(book))
        }
        PageTransition.slideForward(this, intent)
    }

    private fun openReader(book: Book?, page: Int = 42) {
        val intent = Intent(this, ReaderActivity::class.java)
        if (book != null) {
            intent.putExtra(ReaderActivity.EXTRA_BOOK, BookCatalogRepository.enrichBook(book))
        }
        intent.putExtra(ReaderActivity.EXTRA_PAGE, page)
        PageTransition.slideForward(this, intent)
    }

    override fun onResume() {
        super.onResume()
        bindFavorites()
    }

    private fun bindFavorites() {
        val favorites = BookActivityStore.getFavorites(this)
        findViewById<TextView>(R.id.tvFavoritesCount)?.text = favorites.size.toString()
        findViewById<TextView>(R.id.tvFavoriteHeaderCount)?.text = "${favorites.size} buku"
        findViewById<LinearLayout>(R.id.layoutFavoriteBook1)?.visibility = android.view.View.GONE
        findViewById<LinearLayout>(R.id.layoutFavoriteBook2)?.visibility = android.view.View.GONE
        findViewById<TextView>(R.id.tvFavoriteEmpty)?.visibility =
            if (favorites.isEmpty()) android.view.View.VISIBLE else android.view.View.GONE
        val container = findViewById<LinearLayout>(R.id.favoriteListContainer) ?: return
        container.removeAllViews()
        favorites.forEach { book -> container.addView(createFavoriteCard(book)) }
        bindSavedBookmarks()
    }

    private fun bindSavedBookmarks() {
        val bookmarks = BookActivityStore.getBookmarks(this)
        val section = findViewById<LinearLayout>(R.id.savedBookmarksSection) ?: return
        val container = findViewById<LinearLayout>(R.id.savedBookmarksListContainer) ?: return
        section.visibility = if (bookmarks.isEmpty()) android.view.View.GONE else android.view.View.VISIBLE
        findViewById<TextView>(R.id.tvSavedBookmarksCount)?.text = "${bookmarks.size} halaman"
        container.removeAllViews()
        bookmarks.forEach { bookmark -> container.addView(createSavedBookmarkCard(bookmark)) }
    }

    private fun createSavedBookmarkCard(bookmark: SavedPageBookmark): LinearLayout {
        val book = bookmark.book
        val row = LinearLayout(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { bottomMargin = dp(20) }
            orientation = LinearLayout.HORIZONTAL
            isClickable = true
            isFocusable = true
            setBackgroundResource(android.R.drawable.list_selector_background)
        }
        val fallback = BookCoverResolver.localCover(book) ?: R.drawable.img_kepemimpinan_cover
        val coverCard = MaterialCardView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(80), dp(115))
            radius = dp(6).toFloat()
            cardElevation = dp(2).toFloat()
            setCardBackgroundColor(getColor(R.color.card_cream))
            strokeWidth = 0
        }
        val cover = ImageView(this).apply {
            layoutParams = ViewGroup.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.MATCH_PARENT
            )
            contentDescription = "Sampul ${book.title}"
            scaleType = ImageView.ScaleType.CENTER_CROP
        }
        Glide.with(this).load(book.cover_url ?: fallback).placeholder(fallback).error(fallback).into(cover)
        coverCard.addView(cover)
        row.addView(coverCard)

        val details = LinearLayout(this).apply {
            orientation = LinearLayout.VERTICAL
            layoutParams = LinearLayout.LayoutParams(0, ViewGroup.LayoutParams.WRAP_CONTENT, 1f)
                .apply { marginStart = dp(14) }
        }
        details.addView(TextView(this).apply {
            text = book.title
            setTextColor(getColor(R.color.primary_green))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 14f
            setTypeface(typeface, android.graphics.Typeface.BOLD)
            maxLines = 2
            ellipsize = android.text.TextUtils.TruncateAt.END
        })
        details.addView(TextView(this).apply {
            text = book.author
            setTextColor(getColor(R.color.text_muted))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 12f
            maxLines = 1
            ellipsize = android.text.TextUtils.TruncateAt.END
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(3) }
        })

        // Bookmark page tag
        val bookmarkTag = LinearLayout(this).apply {
            orientation = LinearLayout.HORIZONTAL
            gravity = android.view.Gravity.CENTER_VERTICAL
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(4) }
        }
        bookmarkTag.addView(ImageView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(12), dp(12)).apply { marginEnd = dp(4) }
            contentDescription = "Penanda"
            setImageResource(R.drawable.ic_bookmark)
            imageTintList = android.content.res.ColorStateList.valueOf(getColor(R.color.text_muted))
        })
        bookmarkTag.addView(TextView(this).apply {
            text = "Halaman ${bookmark.page} ditandai"
            setTextColor(getColor(R.color.text_muted))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 11.5f
            maxLines = 1
            ellipsize = android.text.TextUtils.TruncateAt.END
        })
        details.addView(bookmarkTag)

        // Action Link: Lanjutkan membaca
        val readButton = LinearLayout(this).apply {
            orientation = LinearLayout.HORIZONTAL
            gravity = android.view.Gravity.CENTER_VERTICAL
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(6) }
        }
        readButton.addView(TextView(this).apply {
            text = "Lanjutkan membaca"
            setTextColor(getColor(R.color.primary_green))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 12f
            setTypeface(typeface, android.graphics.Typeface.BOLD)
        })
        readButton.addView(ImageView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(12), dp(12)).apply { marginStart = dp(3) }
            setImageResource(R.drawable.ic_arrow_right)
            imageTintList = android.content.res.ColorStateList.valueOf(getColor(R.color.primary_green))
        })
        details.addView(readButton)

        row.addView(details)
        row.setOnClickListener { openReader(book, bookmark.page) }
        return row
    }

    private fun createFavoriteCard(book: Book): LinearLayout {
        val row = LinearLayout(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { bottomMargin = dp(20) }
            orientation = LinearLayout.HORIZONTAL
            isClickable = true
            isFocusable = true
        }
        val fallback = BookCoverResolver.localCover(book) ?: R.drawable.img_kepemimpinan_cover
        val coverCard = MaterialCardView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(80), dp(115))
            radius = dp(6).toFloat()
            cardElevation = dp(2).toFloat()
            setCardBackgroundColor(getColor(R.color.card_cream))
            strokeWidth = 0
        }
        val cover = ImageView(this).apply {
            layoutParams = ViewGroup.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.MATCH_PARENT
            )
            contentDescription = "Sampul ${book.title}"
            scaleType = ImageView.ScaleType.CENTER_CROP
        }
        Glide.with(this).load(book.cover_url ?: fallback).placeholder(fallback).error(fallback).into(cover)
        coverCard.addView(cover)
        row.addView(coverCard)

        val details = LinearLayout(this).apply {
            orientation = LinearLayout.VERTICAL
            layoutParams = LinearLayout.LayoutParams(0, ViewGroup.LayoutParams.WRAP_CONTENT, 1f)
                .apply { marginStart = dp(14) }
        }
        details.addView(TextView(this).apply {
            text = book.title
            setTextColor(getColor(R.color.primary_green))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 14f
            setTypeface(typeface, android.graphics.Typeface.BOLD)
            maxLines = 3
            ellipsize = android.text.TextUtils.TruncateAt.END
        })
        details.addView(TextView(this).apply {
            text = book.author
            setTextColor(getColor(R.color.text_muted))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 12f
            maxLines = 1
            ellipsize = android.text.TextUtils.TruncateAt.END
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(4) }
        })
        val savedInfo = LinearLayout(this).apply {
            orientation = LinearLayout.HORIZONTAL
            gravity = android.view.Gravity.CENTER_VERTICAL
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(4) }
        }
        savedInfo.addView(ImageView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(12), dp(12)).apply { marginEnd = dp(4) }
            contentDescription = "Favorit"
            setImageResource(R.drawable.ic_heart)
            imageTintList = android.content.res.ColorStateList.valueOf(getColor(R.color.text_muted))
        })
        savedInfo.addView(TextView(this).apply {
            text = BookActivityStore.getFavoriteSavedDate(this@HistoryFavoriteActivity, book)
            setTextColor(getColor(R.color.text_muted))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 11.5f
            maxLines = 1
            ellipsize = android.text.TextUtils.TruncateAt.END
        })
        details.addView(savedInfo)
        val readButton = TextView(this).apply {
            text = "Baca Lagi?"
            setTextColor(getColor(R.color.primary_green))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 11f
            setTypeface(typeface, android.graphics.Typeface.BOLD)
            setPadding(dp(10), dp(4), dp(10), dp(4))
            setBackgroundResource(R.drawable.bg_chip_unselected)
            backgroundTintList = android.content.res.ColorStateList.valueOf(
                android.graphics.Color.parseColor("#E8F2EC")
            )
            isClickable = true
            isFocusable = true
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(6) }
        }
        details.addView(readButton)
        row.addView(details)
        row.setOnClickListener { openBookDetail(book) }
        readButton.setOnClickListener { openReader(book, 42) }
        return row
    }

    private fun dp(value: Int): Int = (value * resources.displayMetrics.density).toInt()

    private fun openBookDetail(book: Book) {
        val enriched = BookCatalogRepository.enrichBook(book)
        val intent = Intent(this, BookDetailActivity::class.java).apply {
            putExtra("BOOK_ID", enriched.id)
            putExtra("BOOK_OBJECT", enriched)
            putExtra("FROM_SHELF", false)
        }
        PageTransition.slideForward(this, intent)
    }

}
