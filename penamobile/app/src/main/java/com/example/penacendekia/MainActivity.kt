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
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_main)
        
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

        // Fallback default click listeners immediately so UI is responsive offline
        setupFallbackClickListeners()

        // Smooth entrance animations for home elements
        playSmoothEntranceAnimations()

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
        findViewById<TextView>(R.id.tvGreeting)?.text = "Halo, $userName! Mari membuka wawasan."
        // Refresh home data when resuming to reflect any changes from website admin
        loadHomeData()
    }

    private fun loadHomeData() {
        RetrofitClient.apiService.getHome().enqueue(object : Callback<ApiResponse<HomeData>> {
            override fun onResponse(
                call: Call<ApiResponse<HomeData>>,
                response: Response<ApiResponse<HomeData>>
            ) {
                if (isFinishing || isDestroyed) return
                if (response.isSuccessful) {
                    val homeData = response.body()?.data ?: return
                    Log.i(TAG, "Home data loaded successfully: total_books=${homeData.total_books}, ebooks=${homeData.ebooks?.size}")
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
        val serverGreeting = homeData.greeting
        val formattedGreeting = if (!serverGreeting.isNullOrBlank()) {
            if (userName != UserSessionManager.DEFAULT_NAME && serverGreeting.contains("Alya")) {
                val firstName = userName.trim().split(Regex("\\s+")).firstOrNull() ?: userName
                serverGreeting.replace("Alya", firstName)
            } else {
                serverGreeting
            }
        } else {
            "Halo, $userName! Mari membuka wawasan."
        }
        findViewById<TextView>(R.id.tvGreeting)?.text = formattedGreeting

        // 2. Render live E-Books added from website admin or fallback to 11 e-books
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
        renderEbooks(displayEbooks)

        // 3. Render latest book / ebook
        homeData.latest_book?.let { latest ->
            findViewById<TextView>(R.id.tvBookTitleLatest)?.text = latest.title
            val authorCategory = listOfNotNull(
                latest.author.takeIf { it.isNotBlank() },
                latest.category?.takeIf { it.isNotBlank() }
            ).joinToString(" · ")
            findViewById<TextView>(R.id.tvBookAuthorLatest)?.text = authorCategory
            findViewById<View>(R.id.layoutBookLatest)?.setOnClickListener {
                openBookDetail(latest)
            }
            val ivLatest = findViewById<ImageView>(R.id.ivBookCoverLatest)
            if (ivLatest != null) {
                val formattedUrl = RetrofitClient.getFormattedImageUrl(latest.cover_url)
                val localCover = BookCoverResolver.localCover(latest)
                val fallbackCover = BookCoverResolver.placeholder(latest)
                try {
                    Glide.with(this)
                        .load(formattedUrl ?: localCover ?: fallbackCover)
                        .placeholder(fallbackCover)
                        .error(fallbackCover)
                        .into(ivLatest)
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
        }

        // 4. Render editor pick
        homeData.editor_pick?.let { editorPick ->
            findViewById<TextView>(R.id.tvBookTitleEditor)?.text = editorPick.title
            findViewById<View>(R.id.layoutBookEditor)?.setOnClickListener {
                openBookDetail(editorPick)
            }
            val ivEditor = findViewById<ImageView>(R.id.ivBookCoverEditor)
            if (ivEditor != null) {
                val formattedUrl = RetrofitClient.getFormattedImageUrl(editorPick.cover_url)
                val localCover = BookCoverResolver.localCover(editorPick)
                val fallbackCover = BookCoverResolver.placeholder(editorPick)
                try {
                    Glide.with(this)
                        .load(formattedUrl ?: localCover ?: fallbackCover)
                        .placeholder(fallbackCover)
                        .error(fallbackCover)
                        .into(ivEditor)
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
        }

        // 5. Render live popular books into Buku Terbitan horizontal container
        val popular = homeData.popular_books.orEmpty()
        renderPopularBooks(popular)
    }

    private fun renderPopularBooks(books: List<Book>) {
        val container = findViewById<LinearLayout>(R.id.layoutBukuTerbitanContainer) ?: return
        if (books.isEmpty()) return

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
            val formattedUrl = RetrofitClient.getFormattedImageUrl(book.cover_url)
            val localCover = BookCoverResolver.localCover(book)
            val fallbackCover = BookCoverResolver.placeholder(book)
            Glide.with(this)
                .load(formattedUrl ?: localCover ?: fallbackCover)
                .placeholder(fallbackCover)
                .error(fallbackCover)
                .into(ivCover)
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
        container.removeAllViews()

        if (ebooks.isEmpty()) {
            findViewById<View>(R.id.layoutEbooksSectionHeader)?.visibility = View.GONE
            findViewById<View>(R.id.scrollEbooks)?.visibility = View.GONE
            return
        }

        findViewById<View>(R.id.layoutEbooksSectionHeader)?.visibility = View.VISIBLE
        findViewById<View>(R.id.scrollEbooks)?.visibility = View.VISIBLE
        findViewById<TextView>(R.id.tvEbookBadgeCount)?.text = "${ebooks.size} E-Book"

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

            val formattedUrl = RetrofitClient.getFormattedImageUrl(ebook.cover_url)
            val localCover = BookCoverResolver.localCover(ebook)
            val placeholder = BookCoverResolver.placeholder(ebook)
            Glide.with(this)
                .load(formattedUrl ?: localCover ?: placeholder)
                .placeholder(placeholder)
                .error(placeholder)
                .into(ivCover)

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
        findViewById<TextView>(R.id.tvGreeting)?.text = "Halo, $userName! Mari membuka wawasan."

        val designedBooks = designedHomeBooks()
        findViewById<TextView>(R.id.tvBookTitle1)?.text = designedBooks[0].title
        findViewById<TextView>(R.id.tvBookAuthor1)?.text = designedBooks[0].author
        findViewById<TextView>(R.id.tvBookTitle2)?.text = designedBooks[1].title
        findViewById<TextView>(R.id.tvBookAuthor2)?.text = designedBooks[1].author
        findViewById<TextView>(R.id.tvBookTitle3)?.text = designedBooks[2].title
        findViewById<TextView>(R.id.tvBookAuthor3)?.text = designedBooks[2].author
        findViewById<TextView>(R.id.tvBookTitleLatest)?.text = designedBooks[3].title
        findViewById<TextView>(R.id.tvBookAuthorLatest)?.text =
            "${designedBooks[3].author} · ${designedBooks[3].category}"
        findViewById<TextView>(R.id.tvBookTitleEditor)?.text = designedBooks[4].title

        val homeBookCards = listOf(
            R.id.layoutBookItem1,
            R.id.layoutBookItem2,
            R.id.layoutBookItem3,
            R.id.layoutBookLatest,
            R.id.layoutBookEditor
        )
        homeBookCards.forEachIndexed { index, viewId ->
            findViewById<View>(viewId)?.setOnClickListener {
                openBookDetail(designedBooks[index])
            }
        }

        // Tampilkan juga seluruh e-book pada horizontal carousel e-book di Home
        renderEbooks(designedBooks)
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
