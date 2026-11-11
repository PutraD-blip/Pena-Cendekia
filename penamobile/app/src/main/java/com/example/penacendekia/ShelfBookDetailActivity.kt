package com.example.penacendekia

import android.content.Intent
import android.os.Build
import android.os.Bundle
import android.widget.Button
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import androidx.activity.addCallback
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.bumptech.glide.Glide
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.repository.BookCatalogRepository
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.bottomnavigation.BottomNavigationView

class ShelfBookDetailActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView
    private lateinit var shelfBook: Book

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_shelf_book_detail)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.toolbarShelfDetail)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            val lp = v.layoutParams as? android.view.ViewGroup.MarginLayoutParams
            if (lp != null && lp.topMargin != systemBars.top) {
                lp.topMargin = systemBars.top
                v.layoutParams = lp
            }
            insets
        }

        // Back button action
        findViewById<ImageView>(R.id.btnBackShelfDetail)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@ShelfBookDetailActivity)
        }
        val passedBook: Book? = try {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
                intent.getSerializableExtra("BOOK_OBJECT", Book::class.java)
                    ?: intent.getSerializableExtra(ReaderActivity.EXTRA_BOOK, Book::class.java)
            } else {
                @Suppress("DEPRECATION")
                (intent.getSerializableExtra("BOOK_OBJECT") as? Book)
                    ?: (intent.getSerializableExtra(ReaderActivity.EXTRA_BOOK) as? Book)
            }
        } catch (e: Exception) {
            null
        }

        val defaultBook = Book(
            id = -1,
            title = findViewById<TextView>(R.id.tvShelfDetailTitle)?.text?.toString().orEmpty(),
            author = findViewById<TextView>(R.id.tvShelfDetailAuthor)?.text?.toString().orEmpty(),
            category = findViewById<TextView>(R.id.tvShelfDetailCategory)?.text?.toString()
        )
        shelfBook = BookCatalogRepository.enrichBook(passedBook ?: defaultBook)

        // Bind data ke komponen tampilan layout
        findViewById<TextView>(R.id.tvShelfDetailTitle)?.text = shelfBook.title
        findViewById<TextView>(R.id.tvShelfDetailAuthor)?.text = shelfBook.author
        findViewById<TextView>(R.id.tvShelfDetailCategory)?.text = shelfBook.category ?: "Umum"
        findViewById<TextView>(R.id.tvShelfDetailSynopsis)?.text = shelfBook.synopsis.orEmpty()
        findViewById<TextView>(R.id.tvShelfDetailPublisher)?.text = "${shelfBook.publisher ?: "PT. Pena Cendekia Pustaka"} · ${shelfBook.year ?: "2024"}"
        findViewById<TextView>(R.id.tvShelfDetailIsbn)?.text = "ISBN: ${shelfBook.isbn ?: "978-623-8123-01-4"}"
        val ivCover = findViewById<ImageView>(R.id.ivShelfDetailCover)
        if (ivCover != null) {
            BookCoverResolver.loadCover(ivCover, shelfBook)
        }
        findViewById<ImageView>(R.id.btnFavoriteShelfDetail)?.setOnClickListener {
            val isFavorite = BookActivityStore.toggleFavorite(this, shelfBook)
            updateFavoriteStatus(isFavorite)
            Toast.makeText(
                this,
                if (isFavorite) "Ditambahkan ke favorit." else "Dihapus dari favorit.",
                Toast.LENGTH_SHORT
            ).show()
        }
        updateFavoriteStatus(BookActivityStore.isFavorite(this, shelfBook))

        findViewById<Button>(R.id.btnActionReadShelf)?.setOnClickListener {
            val bookmarked = BookActivityStore.getPageBookmark(this, shelfBook)?.page
            val activeSessionPage = BookActivityStore.getActiveSessions(this)
                .find { BookActivityStore.bookKey(it.book) == BookActivityStore.bookKey(shelfBook) }
                ?.currentPage
            val targetPage = (bookmarked ?: activeSessionPage ?: 1).coerceAtLeast(1)

            PageTransition.slideForward(
                this,
                Intent(this, ReaderActivity::class.java)
                    .putExtra(ReaderActivity.EXTRA_BOOK, shelfBook)
                    .putExtra(ReaderActivity.EXTRA_PAGE, targetPage)
            )
        }

        bottomNav = findViewById(R.id.bottomNavigationShelfDetail)

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

        bottomNav.selectedItemId = R.id.nav_rak
    }

    override fun onResume() {
        super.onResume()
        if (::shelfBook.isInitialized) {
            updateFavoriteStatus(BookActivityStore.isFavorite(this, shelfBook))
        }
        if (::bottomNav.isInitialized) {
            bottomNav.selectedItemId = R.id.nav_rak
        }
    }

    private fun updateFavoriteStatus(isFavorite: Boolean) {
        findViewById<ImageView>(R.id.btnFavoriteShelfDetail)?.apply {
            setImageResource(if (isFavorite) R.drawable.ic_heart else R.drawable.ic_heart_outline)
            contentDescription = if (isFavorite) "Hapus dari favorit" else "Tambahkan ke favorit"
        }
        findViewById<ImageView>(R.id.ivShelfFavoriteStatus)?.setImageResource(
            if (isFavorite) R.drawable.ic_heart else R.drawable.ic_heart_outline
        )
        findViewById<TextView>(R.id.tvShelfFavoriteStatus)?.text =
            if (isFavorite) "Dalam favorit" else "Tambahkan ke favorit"
    }
}
