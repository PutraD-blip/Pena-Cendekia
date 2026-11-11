package com.example.penacendekia

import android.content.Intent
import android.content.res.ColorStateList
import android.graphics.Color
import android.graphics.Typeface
import android.os.Bundle
import android.view.Gravity
import android.view.View
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
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.repository.BookCatalogRepository
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.card.MaterialCardView
import java.text.SimpleDateFormat
import java.util.Date
import java.util.Locale

class HistoryFavoriteActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_history_favorite)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.toolbarHistory)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            val lp = v.layoutParams as? android.view.ViewGroup.MarginLayoutParams
            if (lp != null && lp.topMargin != systemBars.top) {
                lp.topMargin = systemBars.top
                v.layoutParams = lp
            }
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

        // Pastikan kartu dummy statis disembunyikan
        findViewById<View>(R.id.layoutHistoryBook1)?.visibility = View.GONE
        findViewById<View>(R.id.layoutHistoryBook2)?.visibility = View.GONE
        findViewById<View>(R.id.layoutFavoriteBook1)?.visibility = View.GONE
        findViewById<View>(R.id.layoutFavoriteBook2)?.visibility = View.GONE

        bottomNav = findViewById(R.id.bottomNavigationHistory)
        bottomNav.menu.findItem(R.id.nav_rak)?.isChecked = true

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

        renderUserData()
    }

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.menu.findItem(R.id.nav_rak)?.isChecked = true
        }
        renderUserData()
    }

    private fun renderUserData() {
        bindHistory()
        bindFavorites()
        bindSavedBookmarks()
    }

    private fun bindHistory() {
        val finishedBooks = BookActivityStore.getFinishedBooks(this)
        val email = UserSessionManager.getUserEmail(this).trim().lowercase()
        val isAlya = email.contains("alya")
        val displayCount = if (isAlya) finishedBooks.size.coerceAtLeast(18) else finishedBooks.size
        findViewById<TextView>(R.id.tvFinishedBooksCount)?.text = displayCount.toString()
        findViewById<TextView>(R.id.tvHistoryHeaderCount)?.text = "Lihat semua · $displayCount"

        val container = findViewById<LinearLayout>(R.id.historyListContainer) ?: return
        val emptyView = findViewById<TextView>(R.id.tvHistoryEmpty)
        container.removeAllViews()

        if (finishedBooks.isEmpty()) {
            emptyView?.visibility = View.VISIBLE
        } else {
            emptyView?.visibility = View.GONE
            finishedBooks.forEach { item ->
                container.addView(createHistoryCard(item))
            }
        }
    }

    private fun bindFavorites() {
        val favorites = BookActivityStore.getFavorites(this)
        findViewById<TextView>(R.id.tvFavoritesCount)?.text = favorites.size.toString()
        findViewById<TextView>(R.id.tvFavoriteHeaderCount)?.text = "${favorites.size} buku"

        val container = findViewById<LinearLayout>(R.id.favoriteListContainer) ?: return
        val emptyView = findViewById<TextView>(R.id.tvFavoriteEmpty)
        container.removeAllViews()

        if (favorites.isEmpty()) {
            emptyView?.visibility = View.VISIBLE
        } else {
            emptyView?.visibility = View.GONE
            favorites.forEach { book ->
                container.addView(createFavoriteCard(book))
            }
        }
    }

    private fun bindSavedBookmarks() {
        val bookmarks = BookActivityStore.getBookmarks(this)
        val section = findViewById<LinearLayout>(R.id.savedBookmarksSection) ?: return
        val container = findViewById<LinearLayout>(R.id.savedBookmarksListContainer) ?: return

        if (bookmarks.isEmpty()) {
            section.visibility = View.GONE
        } else {
            section.visibility = View.VISIBLE
            findViewById<TextView>(R.id.tvSavedBookmarksCount)?.text = "${bookmarks.size} halaman"
            container.removeAllViews()
            bookmarks.forEach { bookmark ->
                container.addView(createSavedBookmarkCard(bookmark))
            }
        }
    }

    private fun createHistoryCard(finished: FinishedBook): View {
        val book = finished.book
        val row = LinearLayout(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { bottomMargin = dp(20) }
            orientation = LinearLayout.HORIZONTAL
            isClickable = true
            isFocusable = true
        }

        val fallback = BookCoverResolver.localCover(book) ?: R.drawable.img_administrasi_rekam_medis
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
        BookCoverResolver.loadCover(cover, book)
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
            textSize = 14.5f
            setTypeface(typeface, Typeface.BOLD)
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

        val formatter = SimpleDateFormat("d MMMM yyyy", Locale.forLanguageTag("id-ID"))
        val finishedDateStr = "Selesai dibaca ${formatter.format(Date(finished.finishedAt))}"
        details.addView(TextView(this).apply {
            text = finishedDateStr
            setTextColor(getColor(R.color.text_muted))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 11.5f
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(4) }
        })

        // Tag: Selesai Dibaca
        val tagDone = LinearLayout(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(6) }
            background = getDrawable(R.drawable.bg_chip_unselected)
            backgroundTintList = ColorStateList.valueOf(Color.parseColor("#E8F2EC"))
            gravity = Gravity.CENTER_VERTICAL
            orientation = LinearLayout.HORIZONTAL
            setPadding(dp(10), dp(4), dp(10), dp(4))
        }
        tagDone.addView(ImageView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(14), dp(14)).apply { marginEnd = dp(4) }
            setImageResource(R.drawable.ic_check)
            imageTintList = ColorStateList.valueOf(getColor(R.color.primary_green))
            contentDescription = "Selesai"
        })
        tagDone.addView(TextView(this).apply {
            text = "Selesai Dibaca"
            setTextColor(getColor(R.color.primary_green))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 11f
            setTypeface(typeface, Typeface.BOLD)
        })
        details.addView(tagDone)

        // Action: Tulis Ulasan
        val actionReview = LinearLayout(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(8) }
            gravity = Gravity.CENTER_VERTICAL
            orientation = LinearLayout.HORIZONTAL
            isClickable = true
            isFocusable = true
            setPadding(dp(2), dp(2), dp(2), dp(2))
        }
        actionReview.addView(TextView(this).apply {
            text = "Tulis ulasan"
            setTextColor(getColor(R.color.primary_green))
            typeface = ResourcesCompat.getFont(this@HistoryFavoriteActivity, R.font.inter)
            textSize = 12.5f
            setTypeface(typeface, Typeface.BOLD)
        })
        actionReview.addView(ImageView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(14), dp(14)).apply { marginStart = dp(4) }
            setImageResource(R.drawable.ic_arrow_right)
            imageTintList = ColorStateList.valueOf(getColor(R.color.primary_green))
            contentDescription = "Arrow"
        })
        details.addView(actionReview)

        row.addView(details)

        row.setOnClickListener { openBookDetail(book) }
        actionReview.setOnClickListener { openReview(book) }

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
        val fallback = BookCoverResolver.localCover(book) ?: R.drawable.img_kepemimpinan_visioner_cover
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
        BookCoverResolver.loadCover(cover, book)
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
            setTypeface(typeface, Typeface.BOLD)
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
            gravity = Gravity.CENTER_VERTICAL
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(4) }
        }
        savedInfo.addView(ImageView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(12), dp(12)).apply { marginEnd = dp(4) }
            contentDescription = "Favorit"
            setImageResource(R.drawable.ic_heart)
            imageTintList = ColorStateList.valueOf(getColor(R.color.text_muted))
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
            setTypeface(typeface, Typeface.BOLD)
            setPadding(dp(10), dp(4), dp(10), dp(4))
            setBackgroundResource(R.drawable.bg_chip_unselected)
            backgroundTintList = ColorStateList.valueOf(Color.parseColor("#E8F2EC"))
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
        readButton.setOnClickListener { openReader(book, 1) }
        return row
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
        val fallback = BookCoverResolver.localCover(book) ?: R.drawable.img_kepemimpinan_visioner_cover
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
        BookCoverResolver.loadCover(cover, book)
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
            setTypeface(typeface, Typeface.BOLD)
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
            gravity = Gravity.CENTER_VERTICAL
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(4) }
        }
        bookmarkTag.addView(ImageView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(12), dp(12)).apply { marginEnd = dp(4) }
            contentDescription = "Penanda"
            setImageResource(R.drawable.ic_bookmark)
            imageTintList = ColorStateList.valueOf(getColor(R.color.text_muted))
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
            gravity = Gravity.CENTER_VERTICAL
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
            setTypeface(typeface, Typeface.BOLD)
        })
        readButton.addView(ImageView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(12), dp(12)).apply { marginStart = dp(3) }
            setImageResource(R.drawable.ic_arrow_right)
            imageTintList = ColorStateList.valueOf(getColor(R.color.primary_green))
        })
        details.addView(readButton)

        row.addView(details)
        row.setOnClickListener { openReader(book, bookmark.page) }
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

    private fun openReview(book: Book?) {
        val intent = Intent(this, ReviewActivity::class.java)
        if (book != null) {
            intent.putExtra(ReaderActivity.EXTRA_BOOK, BookCatalogRepository.enrichBook(book))
        }
        PageTransition.slideForward(this, intent)
    }

    private fun openReader(book: Book?, page: Int = 1) {
        val intent = Intent(this, ReaderActivity::class.java)
        if (book != null) {
            intent.putExtra(ReaderActivity.EXTRA_BOOK, BookCatalogRepository.enrichBook(book))
        }
        intent.putExtra(ReaderActivity.EXTRA_PAGE, page)
        PageTransition.slideForward(this, intent)
    }
}
