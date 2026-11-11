package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import android.view.ViewGroup
import android.widget.FrameLayout
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.bumptech.glide.Glide
import com.example.penacendekia.data.api.RetrofitClient
import com.example.penacendekia.data.model.ApiResponse
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.model.HomeData
import com.example.penacendekia.data.repository.BookCatalogRepository
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.card.MaterialCardView
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class MainActivity : AppCompatActivity() {

    companion object {
        private const val TAG = "MainActivity"
        private var hasPlayedEntrance = false
    }

    private var renderedLatestBookIds: List<Int> = emptyList()
    private var renderedPopularBookIds: List<Int> = emptyList()
    private var renderedEbookIds: List<Int> = emptyList()
    private var lastLoadTime: Long = 0L

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_main)

        // Clear stale Glide disk and memory caches on startup so re-indexed drawables are fresh
        try {
            com.bumptech.glide.Glide.get(this).clearMemory()
            Thread {
                try {
                    com.bumptech.glide.Glide.get(applicationContext).clearDiskCache()
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }.start()
        } catch (e: Exception) {
            e.printStackTrace()
        }
        
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, 0)
            insets
        }

        // Enable smooth scrolling
        findViewById<androidx.core.widget.NestedScrollView>(R.id.nestedScrollMain)?.isSmoothScrollingEnabled = true

        // Logo Pena Cendekia di pojok kiri dengan animasi sentuhan halus
        val logoClick = View.OnClickListener { view ->
            view.animate()
                .scaleX(0.88f)
                .scaleY(0.88f)
                .setDuration(120)
                .withEndAction {
                    view.animate()
                        .scaleX(1.0f)
                        .scaleY(1.0f)
                        .setDuration(220)
                        .setInterpolator(android.view.animation.OvershootInterpolator(2.2f))
                        .start()
                }
                .start()
            Toast.makeText(this, "Pena Cendekia • Sahabat Literasi & Pendidikan", Toast.LENGTH_SHORT).show()
        }
        findViewById<View>(R.id.cardHomeLogo)?.setOnClickListener(logoClick)
        findViewById<View>(R.id.ivHomeLogo)?.setOnClickListener(logoClick)

        val openSearch = View.OnClickListener {
            PageTransition.slideForward(this, Intent(this, SearchActivity::class.java))
        }
        findViewById<View>(R.id.cardSearch)?.setOnClickListener(openSearch)
        findViewById<View>(R.id.layoutSearchBarContent)?.setOnClickListener(openSearch)

        // Notification icon launches NotificationActivity
        findViewById<View>(R.id.ivNotification)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, NotificationActivity::class.java))
        }

        // Tapping Promo Banner opens CatalogActivity
        findViewById<MaterialCardView>(R.id.cardBanner)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, CatalogActivity::class.java))
        }

        // "Lihat Semua" opens CatalogActivity (Semua)
        findViewById<TextView>(R.id.tvLihatSemua)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, CatalogActivity::class.java))
        }

        // "Lihat semua E-Book" opens CatalogActivity with E-Book filter
        findViewById<TextView>(R.id.tvLihatSemuaEbook)?.setOnClickListener {
            val intent = Intent(this, CatalogActivity::class.java).apply {
                putExtra("CATEGORY_FILTER", "E-Book")
            }
            PageTransition.slideForward(this, intent)
        }

        // Bottom Navigation Logic with smooth fade transitions
        findViewById<BottomNavigationView>(R.id.bottomNavigation)?.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_beranda -> true
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

        // Prioritaskan cache persisten (RAM -> Disk) agar 0 detik tanpa jeda dan tanpa layout shift!
        val cached = BookCatalogRepository.getOrLoadHomeData(this)
        if (cached != null) {
            bindHomeData(cached)
        } else {
            // Fallback default click listeners hanya jika cache sama sekali belum ada (instalasi awal)
            setupFallbackClickListeners()
        }

        // Smooth entrance animations only on cold start, not on every tab switch!
        if (!hasPlayedEntrance) {
            playSmoothEntranceAnimations()
            hasPlayedEntrance = true
        }

        // Load live curated data from Pena Cendekia backend (E-Books & Books)
        loadHomeData()
    }

    private fun playSmoothEntranceAnimations() {
        val header = findViewById<View>(R.id.layoutHeader)
        val searchCard = findViewById<View>(R.id.cardSearch)
        val banner = findViewById<View>(R.id.cardBanner)

        header?.alpha = 0f
        header?.translationY = -24f
        header?.animate()
            ?.alpha(1f)
            ?.translationY(0f)
            ?.setDuration(360)
            ?.setInterpolator(android.view.animation.DecelerateInterpolator())
            ?.start()

        searchCard?.alpha = 0f
        searchCard?.translationY = 28f
        searchCard?.animate()
            ?.alpha(1f)
            ?.translationY(0f)
            ?.setStartDelay(100)
            ?.setDuration(360)
            ?.setInterpolator(android.view.animation.DecelerateInterpolator())
            ?.start()

        banner?.alpha = 0f
        banner?.scaleX = 0.96f
        banner?.scaleY = 0.96f
        banner?.animate()
            ?.alpha(1f)
            ?.scaleX(1.0f)
            ?.scaleY(1.0f)
            ?.setStartDelay(180)
            ?.setDuration(420)
            ?.setInterpolator(android.view.animation.DecelerateInterpolator())
            ?.start()
    }

    override fun onResume() {
        super.onResume()
        findViewById<BottomNavigationView>(R.id.bottomNavigation)?.menu?.findItem(R.id.nav_beranda)?.isChecked = true
        val userName = UserSessionManager.getUserName(this)
        val firstName = userName.trim().split(Regex("\\s+")).firstOrNull()?.takeIf { it.isNotBlank() } ?: "Pembaca"
        findViewById<TextView>(R.id.tvGreeting)?.text = "Halo, $firstName! Mari membuka wawasan."

        // Segera terapkan cache data jika ada agar 0 detik tanpa jeda tampilan
        BookCatalogRepository.getOrLoadHomeData(this)?.let { cached ->
            bindHomeData(cached)
        }

        // Refresh data dari server di background (hindari duplikasi jika baru saja dipanggil saat onCreate)
        if (System.currentTimeMillis() - lastLoadTime > 2500L) {
            loadHomeData()
        }
    }

    private fun loadHomeData() {
        lastLoadTime = System.currentTimeMillis()
        RetrofitClient.apiService.getHome().enqueue(object : Callback<ApiResponse<HomeData>> {
            override fun onResponse(
                call: Call<ApiResponse<HomeData>>,
                response: Response<ApiResponse<HomeData>>
            ) {
                if (isFinishing || isDestroyed) return
                if (response.isSuccessful) {
                    val homeData = response.body()?.data ?: return
                    Log.i(TAG, "Home data loaded successfully: total_books=${homeData.total_books}, ebooks=${homeData.ebooks?.size}")
                    BookCatalogRepository.setHomeData(this@MainActivity, homeData)
                    bindHomeData(homeData)
                } else {
                    Log.w(TAG, "Home API error response: ${response.code()} ${response.message()}")
                }
            }

            override fun onFailure(call: Call<ApiResponse<HomeData>>, t: Throwable) {
                Log.e(TAG, "Failed connecting to server for Home data: ${t.message}", t)
                // Keep offline fallback UI
            }
        })
    }

    private fun bindHomeData(homeData: HomeData) {
        // 1. Dynamic greeting customized with logged-in user's name
        val userName = UserSessionManager.getUserName(this)
        val firstName = userName.trim().split(Regex("\\s+")).firstOrNull()?.takeIf { it.isNotBlank() } ?: "Pembaca"
        val serverGreeting = homeData.greeting
        val formattedGreeting = if (!serverGreeting.isNullOrBlank()) {
            if (serverGreeting.contains("Alya", ignoreCase = true)) {
                serverGreeting.replace(Regex("Alya(\\s+Putri)?", RegexOption.IGNORE_CASE), firstName)
            } else {
                serverGreeting
            }
        } else {
            "Halo, $firstName! Mari membuka wawasan."
        }
        findViewById<TextView>(R.id.tvGreeting)?.text = formattedGreeting

        // 2. Render live E-Books (cukup 3 saja di beranda, tombol Lihat Semua menuju Katalog)
        val liveEbooks = homeData.ebooks.orEmpty()
        val displayEbooks = if (liveEbooks.isNotEmpty()) {
            val serverTitles = liveEbooks.map { BookCatalogRepository.normalizeTitle(it.title) }.toSet()
            val localEbooks = BookCatalogRepository.ALL_11_BOOKS.filter {
                BookCatalogRepository.normalizeTitle(it.title) !in serverTitles
            }
            liveEbooks.map { BookCatalogRepository.enrichBook(it) } + localEbooks
        } else {
            BookCatalogRepository.ALL_11_BOOKS
        }
        val totalEbookCount = displayEbooks.size
        findViewById<TextView>(R.id.tvEbookBadgeCount)?.text = "$totalEbookCount E-Book"
        renderEbooks(displayEbooks.take(3))

        // 3. Render 3 Buku Terbaru (maksimal 3 buku, format kartu mirip katalog)
        val rawLatestBooks = if (!homeData.latest_books.isNullOrEmpty()) {
            homeData.latest_books
        } else if (homeData.latest_book != null) {
            listOf(homeData.latest_book)
        } else if (!homeData.ebooks.isNullOrEmpty()) {
            homeData.ebooks.take(3)
        } else {
            designedHomeBooks().take(3)
        }
        val allLatestBooks = rawLatestBooks.map { BookCatalogRepository.enrichBook(it) }
        renderLatestBooks(allLatestBooks)

        // 5. Render live popular books into Buku Terbitan horizontal container
        val popular = homeData.popular_books.orEmpty().map { BookCatalogRepository.enrichBook(it) }
        renderPopularBooks(popular)
    }

    private fun renderPopularBooks(books: List<Book>) {
        val container = findViewById<LinearLayout>(R.id.layoutBukuTerbitanContainer) ?: return
        if (books.isEmpty()) return

        val newIds = books.map { it.id }
        if (newIds == renderedPopularBookIds && container.childCount == books.size) {
            return
        }
        renderedPopularBookIds = newIds

        container.removeAllViews()
        books.forEach { book ->
            val itemView = LinearLayout(this).apply {
                orientation = LinearLayout.VERTICAL
                layoutParams = LinearLayout.LayoutParams(dp(115), ViewGroup.LayoutParams.WRAP_CONTENT).apply {
                    marginEnd = dp(14)
                }
                isClickable = true
                isFocusable = true
                setOnClickListener { openBookDetail(book) }
            }

            val card = MaterialCardView(this).apply {
                layoutParams = LinearLayout.LayoutParams(dp(115), dp(165))
                radius = dp(10).toFloat()
                cardElevation = 0f
                strokeWidth = 0
                setCardBackgroundColor(getColor(R.color.card_cream))
            }

            val ivCover = ImageView(this).apply {
                layoutParams = FrameLayout.LayoutParams(FrameLayout.LayoutParams.MATCH_PARENT, FrameLayout.LayoutParams.MATCH_PARENT)
                scaleType = ImageView.ScaleType.CENTER_CROP
                contentDescription = "Cover ${book.title}"
            }
            BookCoverResolver.loadCover(ivCover, book)
            card.addView(ivCover)
            itemView.addView(card)

            val tvTitle = TextView(this).apply {
                layoutParams = LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT).apply {
                    topMargin = dp(8)
                }
                text = book.title
                setTextColor(getColor(R.color.primary_green))
                textSize = 13f
                setTypeface(typeface, android.graphics.Typeface.BOLD)
                maxLines = 1
                ellipsize = android.text.TextUtils.TruncateAt.END
            }
            itemView.addView(tvTitle)

            val tvAuthor = TextView(this).apply {
                layoutParams = LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT).apply {
                    topMargin = dp(2)
                }
                text = book.author
                setTextColor(getColor(R.color.text_muted))
                textSize = 11f
                maxLines = 1
                ellipsize = android.text.TextUtils.TruncateAt.END
            }
            itemView.addView(tvAuthor)

            val tvRating = TextView(this).apply {
                layoutParams = LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT).apply {
                    topMargin = dp(4)
                }
                text = "★ ${book.rating} · ${book.category ?: "Umum"}"
                setTextColor(getColor(R.color.accent_gold))
                textSize = 11f
                maxLines = 1
                ellipsize = android.text.TextUtils.TruncateAt.END
            }
            itemView.addView(tvRating)

            container.addView(itemView)
        }
    }

    private fun renderEbooks(ebooks: List<Book>) {
        val container = findViewById<LinearLayout>(R.id.layoutEbooksContainer) ?: return

        if (ebooks.isEmpty()) {
            findViewById<View>(R.id.layoutEbooksSectionHeader)?.visibility = View.GONE
            findViewById<View>(R.id.scrollEbooks)?.visibility = View.GONE
            return
        }

        findViewById<View>(R.id.layoutEbooksSectionHeader)?.visibility = View.VISIBLE
        findViewById<View>(R.id.scrollEbooks)?.visibility = View.VISIBLE
        findViewById<TextView>(R.id.tvEbookBadgeCount)?.text = "${ebooks.size} E-Book"

        val newIds = ebooks.map { it.id }
        if (newIds == renderedEbookIds && container.childCount == ebooks.size) {
            return
        }
        renderedEbookIds = newIds

        container.removeAllViews()

        ebooks.forEach { ebook ->
            val itemView = LinearLayout(this).apply {
                orientation = LinearLayout.VERTICAL
                layoutParams = LinearLayout.LayoutParams(dp(125), ViewGroup.LayoutParams.WRAP_CONTENT).apply {
                    marginEnd = dp(14)
                }
                isClickable = true
                isFocusable = true
                setOnClickListener { openBookDetail(ebook) }
            }

            // Material card for Cover
            val card = MaterialCardView(this).apply {
                layoutParams = LinearLayout.LayoutParams(dp(125), dp(175))
                radius = dp(10).toFloat()
                cardElevation = 0f
                strokeWidth = dp(1)
                setStrokeColor(getColor(R.color.divider_color))
                setCardBackgroundColor(getColor(R.color.card_cream))
            }

            val frame = FrameLayout(this).apply {
                layoutParams = FrameLayout.LayoutParams(FrameLayout.LayoutParams.MATCH_PARENT, FrameLayout.LayoutParams.MATCH_PARENT)
            }

            val ivCover = ImageView(this).apply {
                layoutParams = FrameLayout.LayoutParams(FrameLayout.LayoutParams.MATCH_PARENT, FrameLayout.LayoutParams.MATCH_PARENT)
                scaleType = ImageView.ScaleType.CENTER_CROP
                contentDescription = "Cover ${ebook.title}"
            }

            BookCoverResolver.loadCover(ivCover, ebook)

            frame.addView(ivCover)

            // E-BOOK chip badge top-left overlay
            val badge = TextView(this).apply {
                layoutParams = FrameLayout.LayoutParams(FrameLayout.LayoutParams.WRAP_CONTENT, FrameLayout.LayoutParams.WRAP_CONTENT).apply {
                    leftMargin = dp(6)
                    topMargin = dp(6)
                }
                background = getDrawable(R.drawable.bg_chip_selected)
                setPadding(dp(7), dp(3), dp(7), dp(3))
                text = "E-BOOK"
                setTextColor(getColor(R.color.white))
                textSize = 9f
                setTypeface(typeface, android.graphics.Typeface.BOLD)
            }
            frame.addView(badge)

            card.addView(frame)
            itemView.addView(card)

            // Title
            val tvTitle = TextView(this).apply {
                layoutParams = LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT).apply {
                    topMargin = dp(8)
                }
                text = ebook.title
                setTextColor(getColor(R.color.primary_green))
                textSize = 13f
                setTypeface(typeface, android.graphics.Typeface.BOLD)
                maxLines = 2
                ellipsize = android.text.TextUtils.TruncateAt.END
            }
            itemView.addView(tvTitle)

            // Author
            val tvAuthor = TextView(this).apply {
                layoutParams = LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT).apply {
                    topMargin = dp(2)
                }
                text = ebook.author
                setTextColor(getColor(R.color.text_muted))
                textSize = 11f
                maxLines = 1
                ellipsize = android.text.TextUtils.TruncateAt.END
            }
            itemView.addView(tvAuthor)

            // Price & rating
            val tvTag = TextView(this).apply {
                layoutParams = LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT).apply {
                    topMargin = dp(3)
                }
                val priceLabel = if (ebook.is_free || ebook.price <= 0.0) "Gratis" else (ebook.formatted_price ?: "Rp ${ebook.price}")
                val specificCategory = ebook.category?.replace("E-Book · ", "")?.replace("E-Book / ", "") ?: "E-Book"
                text = "★ ${ebook.rating} · $specificCategory · $priceLabel"
                setTextColor(getColor(R.color.accent_gold))
                textSize = 11f
                setTypeface(typeface, android.graphics.Typeface.BOLD)
                maxLines = 1
                ellipsize = android.text.TextUtils.TruncateAt.END
            }
            itemView.addView(tvTag)

            container.addView(itemView)
        }
    }

    private fun dp(value: Int): Int = (value * resources.displayMetrics.density).toInt()

    private fun setupFallbackClickListeners() {
        val userName = UserSessionManager.getUserName(this)
        val firstName = userName.trim().split(Regex("\\s+")).firstOrNull()?.takeIf { it.isNotBlank() } ?: "Pembaca"
        findViewById<TextView>(R.id.tvGreeting)?.text = "Halo, $firstName! Mari membuka wawasan."

        val designedBooks = designedHomeBooks()

        // Render Buku Terbitan secara dinamis dan instan
        renderPopularBooks(designedBooks.take(4))

        // Tampilkan 3 buku terbaru default dengan format kartu katalog
        renderLatestBooks(designedBooks.take(3))

        // Tampilkan juga seluruh e-book pada horizontal carousel e-book di Home
        renderEbooks(designedBooks)
    }

    private fun renderLatestBooks(books: List<Book>) {
        val container = findViewById<LinearLayout>(R.id.layoutBukuTerbaruContainer) ?: return
        if (books.isEmpty()) return

        // Maksimal 3 buku terbaru:
        // Jika admin mengunggah buku lebih dari 3 (misal upload buku ke-4),
        // buku ke-3 bergeser ke posisi ke-2 dan buku ke-4 tampil di posisi ke-3
        val displayBooks = if (books.size > 3) books.takeLast(3) else books

        val newIds = displayBooks.map { it.id }
        if (newIds == renderedLatestBookIds && container.childCount > 0) {
            return
        }
        renderedLatestBookIds = newIds

        container.removeAllViews()

        for (i in 0 until 3) {
            if (i < displayBooks.size) {
                val bookCard = createHomeBookCard(displayBooks[i], hasRightMargin = (i < 2))
                container.addView(bookCard)
            } else {
                val spacer = View(this).apply {
                    layoutParams = LinearLayout.LayoutParams(0, 0, 1f).apply {
                        if (i < 2) marginEnd = dp(9)
                    }
                    visibility = View.INVISIBLE
                }
                container.addView(spacer)
            }
        }
    }

    private fun createHomeBookCard(book: Book, hasRightMargin: Boolean): View {
        val col = LinearLayout(this).apply {
            orientation = LinearLayout.VERTICAL
            layoutParams = LinearLayout.LayoutParams(0, ViewGroup.LayoutParams.WRAP_CONTENT, 1f).apply {
                if (hasRightMargin) marginEnd = dp(9)
            }
            isClickable = true
            isFocusable = true
            setOnClickListener { openBookDetail(book) }
        }

        // Kartu sampul buku (128dp persis seperti di halaman katalog)
        val card = MaterialCardView(this).apply {
            layoutParams = LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, dp(128))
            radius = dp(7).toFloat()
            cardElevation = dp(2).toFloat()
            strokeWidth = 0
            setCardBackgroundColor(getColor(R.color.card_cream))
        }

        val frame = FrameLayout(this).apply {
            layoutParams = FrameLayout.LayoutParams(FrameLayout.LayoutParams.MATCH_PARENT, FrameLayout.LayoutParams.MATCH_PARENT)
        }

        val ivCover = ImageView(this).apply {
            layoutParams = FrameLayout.LayoutParams(FrameLayout.LayoutParams.MATCH_PARENT, FrameLayout.LayoutParams.MATCH_PARENT)
            scaleType = ImageView.ScaleType.CENTER_CROP
            contentDescription = book.title
        }

        BookCoverResolver.loadCover(ivCover, book)
        frame.addView(ivCover)

        // Overlay Badge "E-BOOK" di pojok kiri atas
        val badge = TextView(this).apply {
            layoutParams = FrameLayout.LayoutParams(FrameLayout.LayoutParams.WRAP_CONTENT, FrameLayout.LayoutParams.WRAP_CONTENT).apply {
                leftMargin = dp(5)
                topMargin = dp(5)
            }
            background = getDrawable(R.drawable.bg_chip_selected)
            setPadding(dp(5), dp(2), dp(5), dp(2))
            text = "E-BOOK"
            setTextColor(getColor(R.color.white))
            textSize = 8.5f
            setTypeface(typeface, android.graphics.Typeface.BOLD)
        }
        frame.addView(badge)

        card.addView(frame)
        col.addView(card)

        // Judul Buku (Maksimal 2 baris agar seragam dengan halaman katalog)
        val tvTitle = TextView(this).apply {
            layoutParams = LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT).apply {
                topMargin = dp(5)
            }
            text = book.title
            setTextColor(getColor(R.color.primary_green))
            textSize = 11f
            setTypeface(typeface, android.graphics.Typeface.BOLD)
            maxLines = 2
            ellipsize = android.text.TextUtils.TruncateAt.END
        }
        col.addView(tvTitle)

        // Penulis Buku
        val tvAuthor = TextView(this).apply {
            layoutParams = LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT).apply {
                topMargin = dp(1)
            }
            text = book.author
            setTextColor(getColor(R.color.text_muted))
            textSize = 9.5f
            maxLines = 1
            ellipsize = android.text.TextUtils.TruncateAt.END
        }
        col.addView(tvAuthor)

        // Rating & Kategori
        val specificCategory = book.category?.replace("E-Book · ", "")?.replace("E-Book / ", "") ?: "E-Book"
        val tvRating = TextView(this).apply {
            layoutParams = LinearLayout.LayoutParams(ViewGroup.LayoutParams.MATCH_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT).apply {
                topMargin = dp(2)
            }
            text = "★ ${book.rating} · $specificCategory"
            setTextColor(android.graphics.Color.parseColor("#B87B2B"))
            textSize = 9f
            maxLines = 1
            ellipsize = android.text.TextUtils.TruncateAt.END
        }
        col.addView(tvRating)

        return col
    }

    private fun designedHomeBooks(): List<Book> = BookCatalogRepository.ALL_11_BOOKS

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
