package com.example.penacendekia

import android.content.Intent
import android.content.res.ColorStateList
import android.graphics.Color
import android.graphics.Typeface
import android.os.Bundle
import android.view.Gravity
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.ProgressBar
import android.widget.RelativeLayout
import android.widget.TextView
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
import com.google.android.material.button.MaterialButton
import com.google.android.material.card.MaterialCardView

class ShelfActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView
    private lateinit var chipActiveSessions: TextView
    private lateinit var layoutShelfEmptyState: LinearLayout
    private lateinit var layoutActiveSessionsContainer: LinearLayout

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_shelf)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutShelfHeader)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        chipActiveSessions = findViewById(R.id.chipActiveSessions)
        layoutShelfEmptyState = findViewById(R.id.layoutShelfEmptyState)
        layoutActiveSessionsContainer = findViewById(R.id.layoutActiveSessionsContainer)

        // Tombol ke Riwayat & Favorit
        findViewById<ImageView>(R.id.ivHistoryShelf)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, HistoryFavoriteActivity::class.java))
        }

        findViewById<View>(R.id.btnOpenHistoryFavorite)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, HistoryFavoriteActivity::class.java))
        }

        // Tombol Jelajahi Katalog saat rak kosong
        findViewById<Button>(R.id.btnExploreFromShelf)?.setOnClickListener {
            PageTransition.switchTab(this, CatalogActivity::class.java)
        }

        // Pastikan kartu dummy statis disembunyikan
        findViewById<View>(R.id.cardShelfBook1)?.visibility = View.GONE
        findViewById<View>(R.id.cardShelfBook2)?.visibility = View.GONE

        // Handle Back button to return to Beranda
        onBackPressedDispatcher.addCallback(this, object : androidx.activity.OnBackPressedCallback(true) {
            override fun handleOnBackPressed() {
                PageTransition.switchTab(this@ShelfActivity, MainActivity::class.java)
            }
        })

        // Bottom Navigation Bar
        bottomNav = findViewById(R.id.bottomNavigationShelf)
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
                R.id.nav_rak -> true
                R.id.nav_profil -> {
                    PageTransition.switchTab(this, ProfileActivity::class.java)
                    true
                }
                else -> false
            }
        }
        bottomNav.selectedItemId = R.id.nav_rak

        loadActiveSessions()
    }

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.selectedItemId = R.id.nav_rak
        }
        loadActiveSessions()
    }

    /**
     * Memuat sesi bacaan aktif khusus akun pengguna saat ini.
     * Untuk pengguna baru yang belum membaca buku apa pun, rak akan bersih dengan tampilan Empty State.
     */
    private fun loadActiveSessions() {
        val sessions = BookActivityStore.getActiveSessions(this)
        chipActiveSessions.text = "Sesi Aktif · ${sessions.size}"

        if (sessions.isEmpty()) {
            layoutShelfEmptyState.visibility = View.VISIBLE
            layoutActiveSessionsContainer.visibility = View.GONE
            layoutActiveSessionsContainer.removeAllViews()
        } else {
            layoutShelfEmptyState.visibility = View.GONE
            layoutActiveSessionsContainer.visibility = View.VISIBLE
            layoutActiveSessionsContainer.removeAllViews()

            sessions.forEach { session ->
                layoutActiveSessionsContainer.addView(createActiveSessionCard(session))
            }
        }
    }

    private fun createActiveSessionCard(session: ActiveReadingSession): View {
        val dp = { value: Int -> (value * resources.displayMetrics.density).toInt() }
        val book = session.book
        val enrichedBook = BookCatalogRepository.enrichBook(book)

        val card = MaterialCardView(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { bottomMargin = dp(18) }
            setCardBackgroundColor(Color.WHITE)
            radius = dp(16).toFloat()
            cardElevation = 0f
            strokeColor = Color.parseColor("#E6E9E0")
            strokeWidth = dp(1)
        }

        val rootLayout = LinearLayout(this).apply {
            layoutParams = ViewGroup.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            )
            orientation = LinearLayout.VERTICAL
            setPadding(dp(16), dp(16), dp(16), dp(16))
        }

        // 1. Top Section: Cover & Book Details
        val topLayout = LinearLayout(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            )
            orientation = LinearLayout.HORIZONTAL
        }

        // Book Cover Card
        val coverCard = MaterialCardView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(105), dp(148))
            setCardBackgroundColor(getColor(R.color.card_cream))
            radius = dp(8).toFloat()
            cardElevation = dp(1).toFloat()
            strokeWidth = 0
        }
        val ivCover = ImageView(this).apply {
            layoutParams = ViewGroup.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.MATCH_PARENT
            )
            scaleType = ImageView.ScaleType.CENTER_CROP
            contentDescription = "Sampul ${book.title}"
        }
        BookCoverResolver.loadCover(ivCover, book)
        coverCard.addView(ivCover)
        topLayout.addView(coverCard)

        // Book Info Column
        val infoCol = LinearLayout(this).apply {
            layoutParams = LinearLayout.LayoutParams(0, ViewGroup.LayoutParams.WRAP_CONTENT, 1f).apply {
                marginStart = dp(14)
            }
            orientation = LinearLayout.VERTICAL
        }

        val tvTitle = TextView(this).apply {
            text = book.title
            setTextColor(getColor(R.color.primary_green))
            textSize = 17.5f
            typeface = ResourcesCompat.getFont(this@ShelfActivity, R.font.lora)
            setTypeface(typeface, Typeface.BOLD)
            maxLines = 3
            ellipsize = android.text.TextUtils.TruncateAt.END
        }
        infoCol.addView(tvTitle)

        val tvAuthor = TextView(this).apply {
            text = book.author
            setTextColor(getColor(R.color.text_muted))
            textSize = 12.5f
            typeface = ResourcesCompat.getFont(this@ShelfActivity, R.font.inter)
            maxLines = 2
            ellipsize = android.text.TextUtils.TruncateAt.END
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(5) }
        }
        infoCol.addView(tvAuthor)

        // Time indicator row
        val isAlya = UserSessionManager.getUserEmail(this).trim().lowercase().contains("alya")
        val timeText = if (isAlya) {
            if (book.title.contains("Digitalisasi", ignoreCase = true)) {
                "03 hari 06 jam 19 menit"
            } else if (book.title.contains("Administrasi", ignoreCase = true)) {
                "14 Jam 32 Menit Tersisa"
            } else {
                "Terakhir dibaca baru saja"
            }
        } else {
            "Terakhir dibaca baru saja"
        }
        val subTimeText = if (isAlya) {
            if (book.title.contains("Digitalisasi", ignoreCase = true)) {
                "Sesi Berakhir · 7 Okt, 16.00"
            } else if (book.title.contains("Administrasi", ignoreCase = true)) {
                "Sesi Berakhir · 4 Okt, 12.00"
            } else {
                book.category ?: "E-Book Akademik"
            }
        } else {
            book.category ?: "E-Book Akademik"
        }

        val timeRow = LinearLayout(this).apply {
            orientation = LinearLayout.HORIZONTAL
            gravity = Gravity.CENTER_VERTICAL
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(10) }
        }
        timeRow.addView(ImageView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(14), dp(14))
            setImageResource(R.drawable.ic_clock)
            imageTintList = ColorStateList.valueOf(
                if (isAlya && book.title.contains("Administrasi", ignoreCase = true)) {
                    Color.parseColor("#C27803")
                } else {
                    getColor(R.color.primary_green)
                }
            )
        })
        timeRow.addView(TextView(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { marginStart = dp(6) }
            text = timeText
            setTextColor(
                if (isAlya && book.title.contains("Administrasi", ignoreCase = true)) {
                    Color.parseColor("#C27803")
                } else {
                    getColor(R.color.primary_green)
                }
            )
            textSize = 12f
            typeface = ResourcesCompat.getFont(this@ShelfActivity, R.font.inter)
            setTypeface(typeface, Typeface.BOLD)
        })
        infoCol.addView(timeRow)

        val tvCategory = TextView(this).apply {
            text = subTimeText
            setTextColor(getColor(R.color.text_muted))
            textSize = 11.5f
            typeface = ResourcesCompat.getFont(this@ShelfActivity, R.font.inter)
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(2) }
        }
        infoCol.addView(tvCategory)

        topLayout.addView(infoCol)
        rootLayout.addView(topLayout)

        val bookmarked = BookActivityStore.getPageBookmark(this, enrichedBook)
        val targetPage = (bookmarked?.page ?: session.currentPage).coerceAtLeast(1)

        // 2. Progress Section
        val progressRow = RelativeLayout(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(14) }
        }
        val tvPageInfo = TextView(this).apply {
            text = if (bookmarked != null) {
                "Halaman $targetPage dari ${session.totalPages} (Ditandai 📌)"
            } else {
                "Halaman $targetPage dari ${session.totalPages}"
            }
            setTextColor(getColor(R.color.text_muted))
            textSize = 12f
            typeface = ResourcesCompat.getFont(this@ShelfActivity, R.font.inter)
        }
        val tvPercent = TextView(this).apply {
            text = "${session.progressPercent}%"
            setTextColor(getColor(R.color.primary_green))
            textSize = 12f
            typeface = ResourcesCompat.getFont(this@ShelfActivity, R.font.inter)
            setTypeface(typeface, Typeface.BOLD)
            val params = RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { addRule(RelativeLayout.ALIGN_PARENT_END) }
            layoutParams = params
        }
        progressRow.addView(tvPageInfo)
        progressRow.addView(tvPercent)
        rootLayout.addView(progressRow)

        val progressBar = ProgressBar(this, null, android.R.attr.progressBarStyleHorizontal).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                dp(5)
            ).apply {
                topMargin = dp(6)
                bottomMargin = dp(16)
            }
            max = 100
            progress = session.progressPercent
            progressTintList = ColorStateList.valueOf(getColor(R.color.primary_green))
            progressBackgroundTintList = ColorStateList.valueOf(Color.parseColor("#E4E9E2"))
        }
        rootLayout.addView(progressBar)

        // 3. Action Button: Lanjut Membaca
        val btnRead = MaterialButton(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                dp(48)
            )
            text = "Lanjut membaca"
            textSize = 14f
            isAllCaps = false
            setTextColor(Color.WHITE)
            typeface = ResourcesCompat.getFont(this@ShelfActivity, R.font.inter)
            setTypeface(typeface, Typeface.BOLD)
            backgroundTintList = ColorStateList.valueOf(getColor(R.color.primary_green))
            cornerRadius = dp(10)
            setIconResource(R.drawable.ic_book_open)
            iconGravity = MaterialButton.ICON_GRAVITY_TEXT_START
            iconPadding = dp(8)
            iconTint = ColorStateList.valueOf(Color.WHITE)
        }
        rootLayout.addView(btnRead)

        card.addView(rootLayout)

        // Clicks
        card.setOnClickListener {
            val intent = Intent(this, ShelfBookDetailActivity::class.java).apply {
                putExtra("BOOK_OBJECT", enrichedBook)
                putExtra(ReaderActivity.EXTRA_BOOK, enrichedBook)
            }
            PageTransition.slideForward(this, intent)
        }

        btnRead.setOnClickListener {
            openReader(enrichedBook, targetPage)
        }

        return card
    }

    private fun openReader(book: Book, page: Int = 1) {
        val intent = Intent(this, ReaderActivity::class.java).apply {
            putExtra(ReaderActivity.EXTRA_BOOK, book)
            putExtra(ReaderActivity.EXTRA_PAGE, page)
        }
        PageTransition.slideForward(this, intent)
    }
}
