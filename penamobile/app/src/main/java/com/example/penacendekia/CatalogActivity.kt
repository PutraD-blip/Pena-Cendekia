package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.Gravity
import android.view.View
import android.view.ViewGroup
import android.widget.FrameLayout
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
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
import com.google.android.material.card.MaterialCardView
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class CatalogActivity : AppCompatActivity() {

    companion object {
        private const val TAG = "CatalogActivity"
    }

    private lateinit var bottomNav: BottomNavigationView
    private var activeCategory: String? = null
    private var categoryChips: List<TextView> = emptyList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_catalog)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutCatalogHeader)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        val openSearch = View.OnClickListener {
            PageTransition.slideForward(this, Intent(this, SearchActivity::class.java))
        }
        findViewById<View>(R.id.cardSearchCatalog)?.setOnClickListener(openSearch)
        findViewById<View>(R.id.layoutCatalogSearchContent)?.setOnClickListener(openSearch)

        bottomNav = findViewById(R.id.bottomNavigationCatalog)

        // Bottom Navigation Listener
        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_beranda -> {
                    PageTransition.switchTab(this, MainActivity::class.java)
                    true
                }
                R.id.nav_katalog -> true
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

        // Ensure "Katalog" is active
        bottomNav.selectedItemId = R.id.nav_katalog

        // Setup Interactive Category Chips
        setupCategoryChips()

        // Setup fallback default clicks immediately
        setupFallbackClicks()

        // Check if intent specified a category (e.g. "E-Book" from Home)
        val initialCategory = intent.getStringExtra("CATEGORY_FILTER")
        if (!initialCategory.isNullOrBlank()) {
            selectCategoryChip(initialCategory)
        } else {
            fetchCatalogBooks(null)
        }
    }

    private fun setupCategoryChips() {
        categoryChips = listOfNotNull(
            findViewById(R.id.chipSemua),
            findViewById(R.id.chipEbook),
            findViewById(R.id.chipPendidikan),
            findViewById(R.id.chipReferensi),
            findViewById(R.id.chipSastra),
            findViewById(R.id.chipUmum),
            findViewById(R.id.chipAgama),
            findViewById(R.id.chipBukuAjar),
            findViewById(R.id.chipKedokteranHewan),
            findViewById(R.id.chipKesehatan),
            findViewById(R.id.chipTeknologi),
            findViewById(R.id.chipBisnis)
        )

        categoryChips.forEach { chip ->
            chip.setOnClickListener {
                val categoryName = chip.text.toString().trim()
                selectCategoryChip(categoryName)
            }
        }
    }

    private fun selectCategoryChip(categoryName: String) {
        val normalized = categoryName.trim()
        activeCategory = if (normalized.equals("Semua", ignoreCase = true)) null else normalized

        categoryChips.forEach { chip ->
            val isCurrent = chip.text.toString().trim().equals(normalized, ignoreCase = true)
            if (isCurrent) {
                chip.setBackgroundResource(R.drawable.bg_chip_selected)
                chip.setTextColor(getColor(R.color.white))
            } else {
                chip.setBackgroundResource(R.drawable.bg_chip_unselected)
                chip.setTextColor(getColor(R.color.text_dark))
            }
        }

        val subtitleText = when {
            activeCategory == null -> "Semua buku & e-book terbitan resmi"
            activeCategory.equals("E-Book", ignoreCase = true) -> "Koleksi Lengkap E-Book Digital Pena Cendekia"
            else -> "Kategori: $activeCategory"
        }
        findViewById<TextView>(R.id.tvCatalogSectionSubtitle)?.text = subtitleText

        fetchCatalogBooks(activeCategory)
    }

    private fun fetchCatalogBooks(category: String? = null) {
        val localBooks = BookCatalogRepository.getBooks(category)
        val typeQuery = if (category.equals("E-Book", ignoreCase = true)) "ebook" else null
        val categoryQuery = if (category.equals("E-Book", ignoreCase = true)) null else category

        // Tampilkan langsung 11 buku lokal sesuai kategori agar instan dan responsif
        bindCatalogBooks(localBooks)

        RetrofitClient.apiService.getBooks(category = categoryQuery, type = typeQuery).enqueue(object : Callback<ApiResponse<List<Book>>> {
            override fun onResponse(call: Call<ApiResponse<List<Book>>>, response: Response<ApiResponse<List<Book>>>) {
                if (isFinishing || isDestroyed) return
                if (response.isSuccessful) {
                    val serverBooks = response.body()?.data.orEmpty()
                    Log.i(TAG, "Loaded ${serverBooks.size} books from server for category: $category")
                    val mergedBooks = mergeWithLocalBooks(serverBooks, category)
                    bindCatalogBooks(mergedBooks)
                } else {
                    Log.w(TAG, "Books API error: ${response.code()} ${response.message()}")
                }
            }

            override fun onFailure(call: Call<ApiResponse<List<Book>>>, t: Throwable) {
                Log.e(TAG, "Failed connecting to server for catalog: ${t.message}", t)
                // localBooks sudah ditampilkan
            }
        })
    }

    private fun mergeWithLocalBooks(serverBooks: List<Book>, category: String?): List<Book> {
        val localBooks = BookCatalogRepository.getBooks(category)
        if (serverBooks.isEmpty()) return localBooks

        val enrichedServer = serverBooks.map { BookCatalogRepository.enrichBook(it) }
        val titlesInServer = enrichedServer.map { normalizeTitle(it.title) }.toSet()

        val remainingLocal = localBooks.filter { localBook ->
            normalizeTitle(localBook.title) !in titlesInServer
        }

        return enrichedServer + remainingLocal
    }

    private fun normalizeTitle(text: String): String =
        text.lowercase().replace(Regex("[^a-z0-9]"), "")

    private fun bindCatalogBooks(books: List<Book>) {
        val totalCount = books.size
        val subtitle = when {
            activeCategory.equals("E-Book", ignoreCase = true) -> "$totalCount e-book digital terdaftar di katalog."
            activeCategory != null -> "$totalCount buku & e-book dalam kategori $activeCategory."
            else -> "$totalCount buku & e-book terdaftar, seluas rasa ingin tahu."
        }
        findViewById<TextView>(R.id.tvTotalCatalogSubtitle)?.text = subtitle

        val container = findViewById<LinearLayout>(R.id.layoutCatalogGridContainer) ?: return
        val emptyState = findViewById<View>(R.id.layoutCatalogEmptyState)
        container.removeAllViews()

        if (books.isEmpty()) {
            emptyState?.visibility = View.VISIBLE
            return
        }
        emptyState?.visibility = View.GONE

        // Tampilkan seluruh buku secara dinamis: per-baris 3 buku dengan ukuran proporsional
        val chunks = books.chunked(3)
        chunks.forEach { rowBooks ->
            val rowLayout = LinearLayout(this).apply {
                orientation = LinearLayout.HORIZONTAL
                layoutParams = LinearLayout.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.WRAP_CONTENT
                ).apply {
                    bottomMargin = dp(14)
                }
            }

            for (i in 0 until 3) {
                if (i < rowBooks.size) {
                    val bookCard = createCatalogBookCard(rowBooks[i], hasRightMargin = (i < 2))
                    rowLayout.addView(bookCard)
                } else {
                    val spacer = createSpacer(hasRightMargin = (i < 2))
                    rowLayout.addView(spacer)
                }
            }

            container.addView(rowLayout)
        }
    }

    private fun createCatalogBookCard(book: Book, hasRightMargin: Boolean): View {
        val col = LinearLayout(this).apply {
            orientation = LinearLayout.VERTICAL
            layoutParams = LinearLayout.LayoutParams(0, ViewGroup.LayoutParams.WRAP_CONTENT, 1f).apply {
                if (hasRightMargin) marginEnd = dp(9)
            }
            isClickable = true
            isFocusable = true
            setOnClickListener { openBookDetail(book) }
        }

        // MaterialCardView untuk Sampul Buku (Ukuran proporsional 128dp agar pas dan rapi tanpa scroll berlebih)
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

        val formattedUrl = RetrofitClient.getFormattedImageUrl(book.cover_url)
        val localCover = BookCoverResolver.localCover(book)
        val placeholder = BookCoverResolver.placeholder(book)
        try {
            Glide.with(this)
                .load(formattedUrl ?: localCover ?: placeholder)
                .placeholder(placeholder)
                .error(placeholder)
                .into(ivCover)
        } catch (e: Exception) {
            e.printStackTrace()
        }
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

        // Judul Buku (Maksimal 2 baris agar rapi)
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

        // Rating & Kategori Spesifik
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

    private fun createSpacer(hasRightMargin: Boolean): View {
        return View(this).apply {
            layoutParams = LinearLayout.LayoutParams(0, 0, 1f).apply {
                if (hasRightMargin) marginEnd = dp(9)
            }
            visibility = View.INVISIBLE
        }
    }

    private fun setupFallbackClicks() {
        bindCatalogBooks(BookCatalogRepository.getBooks(activeCategory))
    }

    private fun designedCatalogBooks(): List<Book> = BookCatalogRepository.ALL_11_BOOKS

    private fun dp(value: Int): Int = (value * resources.displayMetrics.density).toInt()

    private fun openBookDetail(book: Book) {
        val intent = Intent(this, BookDetailActivity::class.java).apply {
            putExtra("BOOK_ID", book.id)
            putExtra("BOOK_OBJECT", book)
            putExtra("FROM_SHELF", false)
        }
        PageTransition.slideForward(this, intent)
    }

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.selectedItemId = R.id.nav_katalog
        }
        // Refresh catalog books when resuming
        fetchCatalogBooks(activeCategory)
    }
}
