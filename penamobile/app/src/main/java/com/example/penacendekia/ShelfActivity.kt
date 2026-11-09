package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.ImageView
import android.widget.TextView
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

import com.bumptech.glide.Glide
import com.example.penacendekia.data.api.RetrofitClient
import com.example.penacendekia.data.model.ApiResponse
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.card.MaterialCardView
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class ShelfActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView
    private var shelfBooks: List<Book> = emptyList()

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_shelf)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutShelfHeader)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        // Tapping history icon or filter chip opens HistoryFavoriteActivity
        findViewById<ImageView>(R.id.ivHistoryShelf)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, HistoryFavoriteActivity::class.java))
        }

        findViewById<android.view.View>(R.id.btnOpenHistoryFavorite)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, HistoryFavoriteActivity::class.java))
        }

        // Tapping book card opens ShelfBookDetailActivity (Detail buku sesi aktif)
        findViewById<MaterialCardView>(R.id.cardShelfBook1)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, ShelfBookDetailActivity::class.java))
        }

        findViewById<MaterialCardView>(R.id.cardShelfBook2)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, ShelfBookDetailActivity::class.java))
        }

        // "Lanjut membaca" buttons open ReaderActivity directly
        findViewById<Button>(R.id.btnReadBook1)?.setOnClickListener {
            openReader(
                com.example.penacendekia.data.repository.BookCatalogRepository.findBook("Kepemimpinan Visioner") ?: Book(
                    id = -1,
                    title = "Kepemimpinan Visioner Di Lembaga Pendidikan Islam",
                    author = "Dr. Ahmad Zain Sarnoto",
                    category = "E-Book · Agama",
                    pages = "216"
                )
            )
        }

        findViewById<Button>(R.id.btnReadBook2)?.setOnClickListener {
            openReader(
                com.example.penacendekia.data.repository.BookCatalogRepository.findBook("Digitalisasi Pendidikan") ?: Book(
                    id = -1,
                    title = "Digitalisasi Pendidikan Islam",
                    author = "Dr. Hj. Siti Yumnah, M.Pd.I. Muhammad Sulaiman",
                    category = "E-Book · Teknologi"
                )
            )
        }
        
        // Default click listeners
        setupDefaultClicks()

        // Fetch real books from Pena Cendekia Database API
        loadShelfBooksFromApi()

        bottomNav = findViewById(R.id.bottomNavigationShelf)

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
                R.id.nav_rak -> true
                R.id.nav_profil -> {
                    PageTransition.switchTab(this, ProfileActivity::class.java)
                    true
                }
                else -> false
            }
        }

        bottomNav.selectedItemId = R.id.nav_rak
    }

    private fun setupDefaultClicks() {
        findViewById<MaterialCardView>(R.id.cardShelfBook1)?.setOnClickListener {
            val book = shelfBooks.getOrNull(0)
            openReader(book)
        }
        findViewById<MaterialCardView>(R.id.cardShelfBook2)?.setOnClickListener {
            val book = shelfBooks.getOrNull(1)
            openReader(book)
        }
        findViewById<Button>(R.id.btnReadBook1)?.setOnClickListener {
            val book = shelfBooks.getOrNull(0)
            openReader(book)
        }
        findViewById<Button>(R.id.btnReadBook2)?.setOnClickListener {
            val book = shelfBooks.getOrNull(1)
            openReader(book)
        }
    }

    private fun loadShelfBooksFromApi() {
        RetrofitClient.apiService.getBooks().enqueue(object : Callback<ApiResponse<List<Book>>> {
            override fun onResponse(call: Call<ApiResponse<List<Book>>>, response: Response<ApiResponse<List<Book>>>) {
                if (response.isSuccessful) {
                    val books = response.body()?.data
                    if (!books.isNullOrEmpty()) {
                        shelfBooks = books
                        bindShelfBooks(books)
                    }
                }
            }

            override fun onFailure(call: Call<ApiResponse<List<Book>>>, t: Throwable) {
                // Keep default real book views
            }
        })
    }

    private fun bindShelfBooks(books: List<Book>) {
        // Book 1: Digitalisasi Pendidikan Islam (ID 4 or books[3] / books[0])
        val b1 = books.find { it.title.contains("Digitalisasi", ignoreCase = true) } ?: books[0]
        findViewById<TextView>(R.id.tvShelfTitle1)?.text = b1.title
        findViewById<TextView>(R.id.tvShelfAuthor1)?.text = b1.author
        findViewById<ImageView>(R.id.ivShelfCover1)?.let { iv ->
            val adjustedUrl = b1.cover_url?.replace("127.0.0.1:8000", "10.0.2.2:8000")
            Glide.with(this)
                .load(adjustedUrl)
                .placeholder(R.drawable.img_digitalisasi_cover)
                .error(R.drawable.img_digitalisasi_cover)
                .into(iv)
        }

        // Book 2: Administrasi Rekam Medis (ID 1 or books[1])
        val b2 = books.find { it.title.contains("Administrasi", ignoreCase = true) } ?: (if (books.size > 1) books[1] else books[0])
        findViewById<TextView>(R.id.tvShelfTitle2)?.text = b2.title
        findViewById<TextView>(R.id.tvShelfAuthor2)?.text = b2.author
        findViewById<ImageView>(R.id.ivShelfCover2)?.let { iv ->
            val adjustedUrl = b2.cover_url?.replace("127.0.0.1:8000", "10.0.2.2:8000")
            Glide.with(this)
                .load(adjustedUrl)
                .placeholder(R.drawable.img_administrasi_cover)
                .error(R.drawable.img_administrasi_cover)
                .into(iv)
        }

        // Re-wire click listeners with loaded books
        findViewById<MaterialCardView>(R.id.cardShelfBook1)?.setOnClickListener { openReader(b1) }
        findViewById<Button>(R.id.btnReadBook1)?.setOnClickListener { openReader(b1) }
        findViewById<MaterialCardView>(R.id.cardShelfBook2)?.setOnClickListener { openReader(b2) }
        findViewById<Button>(R.id.btnReadBook2)?.setOnClickListener { openReader(b2) }
    }

    private fun openReader(book: Book?) {
        val intent = Intent(this, ReaderActivity::class.java)
        if (book != null) {
            intent.putExtra(ReaderActivity.EXTRA_BOOK, book)
        }
        PageTransition.slideForward(this, intent)
    }

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.selectedItemId = R.id.nav_rak
        }
    }
}
