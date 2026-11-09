package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.text.Editable
import android.text.TextWatcher
import android.view.inputmethod.EditorInfo
import android.widget.EditText
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import android.view.View
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

class SearchActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView
    private val searchHandler = Handler(Looper.getMainLooper())
    private var searchCall: Call<ApiResponse<List<Book>>>? = null
    private var pendingSearch: Runnable? = null
    private var searchGeneration = 0

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_search)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutSearchHeader)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        val etSearch = findViewById<EditText>(R.id.etSearchQuery)

        // Back button action
        findViewById<ImageView>(R.id.btnBackSearch)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@SearchActivity)
        }

        // Clear search input action
        findViewById<ImageView>(R.id.btnClearSearch)?.setOnClickListener {
            etSearch?.setText("")
        }

        // Keyboard search action listener
        etSearch?.setOnEditorActionListener { _, actionId, _ ->
            if (actionId == EditorInfo.IME_ACTION_SEARCH) {
                scheduleSearch(etSearch.text.toString().trim(), 0)
                true
            } else {
                false
            }
        }

        // TextChange listener for instant search
        etSearch?.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {}
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {
                val query = s?.toString()?.trim() ?: ""
                scheduleSearch(query, if (query.isBlank()) 0 else 300)
            }
            override fun afterTextChanged(s: Editable?) {}
        })

        scheduleSearch("", 0)

        bottomNav = findViewById(R.id.bottomNavigationSearch)
        bottomNav.selectedItemId = R.id.nav_katalog

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
    }

    private fun scheduleSearch(query: String, delayMillis: Long) {
        searchGeneration += 1
        val generation = searchGeneration
        searchCall?.cancel()
        pendingSearch?.let(searchHandler::removeCallbacks)

        if (query.isNotEmpty() && query.length < 2) {
            bindSearchResults(emptyList(), "Ketik minimal 2 karakter")
            return
        }

        bindSearchResults(emptyList(), if (query.isBlank()) "Memuat buku..." else "Mencari...")
        val action = Runnable { fetchSearchResults(query, generation) }
        pendingSearch = action
        searchHandler.postDelayed(action, delayMillis)
    }

    private fun fetchSearchResults(query: String, generation: Int) {
        val searchQuery = query.ifBlank { null }
        val call = RetrofitClient.apiService.getBooks(search = searchQuery)
        searchCall = call
        call.enqueue(object : Callback<ApiResponse<List<Book>>> {
            override fun onResponse(call: Call<ApiResponse<List<Book>>>, response: Response<ApiResponse<List<Book>>>) {
                if (generation != searchGeneration) return

                if (response.isSuccessful) {
                    val serverBooks = response.body()?.data.orEmpty()
                    val matchingServerBooks = if (query.isBlank()) {
                        serverBooks
                    } else {
                        serverBooks.filter { matchesQuery(it, query) }
                    }
                    val localBooks = if (query.isBlank()) {
                        emptyList()
                    } else {
                        fallbackBooks().filter { matchesQuery(it, query) }
                    }
                    val localBooksByTitle = fallbackBooks().associateBy {
                        normalizeForSearch(it.title)
                    }
                    val books = (matchingServerBooks + localBooks)
                        .map { serverBook ->
                            val localBook = localBooksByTitle[normalizeForSearch(serverBook.title)]
                            if (localBook == null) serverBook else localBook.copy(id = serverBook.id)
                        }
                        .distinctBy { normalizeForSearch(it.title) }
                    bindSearchResults(books, "${books.size} buku ditemukan", query)
                } else {
                    val localBooks = if (query.isBlank()) {
                        emptyList()
                    } else {
                        fallbackBooks().filter { matchesQuery(it, query) }
                    }
                    if (localBooks.isNotEmpty()) {
                        bindSearchResults(localBooks, "${localBooks.size} buku ditemukan", query)
                    } else {
                        bindSearchResults(emptyList(), "Pencarian gagal dimuat")
                    }
                }
            }

            override fun onFailure(call: Call<ApiResponse<List<Book>>>, t: Throwable) {
                if (generation != searchGeneration || call.isCanceled) return
                val localBooks = if (query.isBlank()) {
                    emptyList()
                } else {
                    fallbackBooks().filter { matchesQuery(it, query) }
                }
                if (localBooks.isNotEmpty()) {
                    bindSearchResults(localBooks, "${localBooks.size} buku ditemukan", query)
                } else {
                    bindSearchResults(emptyList(), "Pencarian gagal dimuat")
                }
            }
        })
    }

    private fun bindSearchResults(
        books: List<Book>,
        countText: String,
        query: String = ""
    ) {
        val tvCount = findViewById<TextView>(R.id.tvSearchResultCount)
        tvCount?.text = countText
        val resultRow = findViewById<LinearLayout>(R.id.layoutSearchResultBook)
        val relatedRow = findViewById<LinearLayout>(R.id.layoutRelatedBook)
        val divider = findViewById<View>(R.id.searchResultDivider)
        val relatedHeader = findViewById<View>(R.id.layoutRelatedHeader)
        resultRow?.visibility = if (books.isNotEmpty()) View.VISIBLE else View.GONE
        relatedRow?.visibility = if (books.size > 1) View.VISIBLE else View.GONE
        divider?.visibility = if (books.isNotEmpty()) View.VISIBLE else View.GONE
        relatedHeader?.visibility = if (books.size > 1) View.VISIBLE else View.GONE

        if (books.isNotEmpty()) {
            val resultBook = books[0]
            findViewById<TextView>(R.id.tvSearchResultTitle)?.text = resultBook.title
            findViewById<TextView>(R.id.tvSearchResultAuthor)?.text = resultBook.author
            findViewById<TextView>(R.id.tvSearchResultMeta)?.text = "${resultBook.publisher ?: "Pena Pustaka"} · ${resultBook.year ?: "2026"} · ${resultBook.category ?: "Pendidikan"}"
            loadBookCover(findViewById(R.id.ivSearchResultCover), resultBook)

            resultRow?.setOnClickListener {
                openBookDetail(resultBook)
            }
        }

        if (books.size > 1) {
            val relatedBook = books[1]
            findViewById<TextView>(R.id.tvRelatedBookTitle)?.text = relatedBook.title
            findViewById<TextView>(R.id.tvRelatedBookAuthor)?.text = "Penulis: ${relatedBook.author}"
            findViewById<TextView>(R.id.tvRelatedBookMeta)?.text = "${relatedBook.publisher ?: "Pena Pustaka"} · ${relatedBook.year ?: "2026"} · ${relatedBook.category ?: "Pendidikan"}"
            loadBookCover(findViewById(R.id.ivRelatedBookCover), relatedBook)

            relatedRow?.setOnClickListener {
                openBookDetail(relatedBook)
            }
        }

        findViewById<TextView>(R.id.tvRelatedSectionTitle)?.text =
            if (query.isBlank()) "Bacaan terkait" else "Hasil lainnya"
    }

    private fun matchesQuery(book: Book, query: String): Boolean {
        val searchableText = normalizeForSearch(
            listOfNotNull(
                book.title,
                book.subtitle,
                book.author,
                book.editor,
                book.publisher,
                book.category,
                book.isbn
            ).joinToString(" ")
        )
        return normalizeForSearch(query)
            .split(" ")
            .filter(String::isNotBlank)
            .all { token -> searchableText.contains(token) }
    }

    private fun normalizeForSearch(value: String): String =
        java.text.Normalizer.normalize(value, java.text.Normalizer.Form.NFD)
            .replace(Regex("\\p{Mn}+"), "")
            .lowercase()
            .replace(Regex("[^\\p{L}\\p{N}]+"), " ")
            .trim()

    private fun fallbackBooks(): List<Book> = BookCatalogRepository.ALL_11_BOOKS

    private fun loadBookCover(imageView: ImageView?, book: Book) {
        val localCover = BookCoverResolver.localCover(book)
        if (imageView == null || isFinishing || isDestroyed) return
        if (localCover != null) {
            Glide.with(this).load(localCover).into(imageView)
        } else {
            loadCover(imageView, book.cover_url, BookCoverResolver.placeholder(book))
        }
    }

    private fun loadCover(imageView: ImageView?, url: String?, placeholderRes: Int) {
        if (imageView == null || isFinishing || isDestroyed) return
        val formattedUrl = RetrofitClient.getFormattedImageUrl(url)
        try {
            Glide.with(this)
                .load(formattedUrl ?: placeholderRes)
                .placeholder(placeholderRes)
                .error(placeholderRes)
                .into(imageView)
        } catch (e: Exception) {
            e.printStackTrace()
        }
    }

    private fun openBookDetail(book: Book) {
        val intent = Intent(this, BookDetailActivity::class.java).apply {
            putExtra("BOOK_ID", book.id)
            putExtra("BOOK_OBJECT", book)
            putExtra("FROM_SHELF", false)
        }
        PageTransition.slideForward(this, intent)
    }

    override fun onDestroy() {
        searchHandler.removeCallbacksAndMessages(null)
        searchCall?.cancel()
        super.onDestroy()
    }
}
