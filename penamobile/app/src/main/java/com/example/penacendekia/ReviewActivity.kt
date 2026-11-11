package com.example.penacendekia

import android.content.Context
import android.content.Intent
import android.os.Build
import android.os.Bundle
import android.view.inputmethod.InputMethodManager
import android.widget.Button
import android.widget.EditText
import android.widget.ImageView
import android.widget.RatingBar
import android.widget.TextView
import android.widget.Toast
import androidx.activity.addCallback
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.bumptech.glide.Glide
import com.example.penacendekia.data.api.RetrofitClient
import com.example.penacendekia.data.model.ApiResponse
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.model.BookReviewListResponse
import com.example.penacendekia.data.model.BookReviewRecord
import com.example.penacendekia.data.model.BookReviewSubmission
import com.example.penacendekia.data.repository.BookCatalogRepository
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.bottomnavigation.BottomNavigationView
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class ReviewActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView
    private lateinit var reviewBook: Book

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_review)

        val resId = resources.getIdentifier("status_bar_height", "dimen", "android")
        val statusBarHeight = if (resId > 0) resources.getDimensionPixelSize(resId)
        else (24 * resources.displayMetrics.density).toInt()
        val extraTop = (20 * resources.displayMetrics.density).toInt()
        val toolbar = findViewById<android.widget.LinearLayout>(R.id.toolbarReview)
        toolbar.setPadding(toolbar.paddingLeft, statusBarHeight + extraTop, toolbar.paddingRight, toolbar.paddingBottom)
        ViewCompat.setOnApplyWindowInsetsListener(toolbar) { view, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            val topBar = if (systemBars.top > 0) systemBars.top else statusBarHeight
            view.setPadding(view.paddingLeft, topBar + extraTop, view.paddingRight, view.paddingBottom)
            insets
        }

        val initialBook = getBookFromIntent() ?: Book(
            id = -1,
            title = "Kepemimpinan Visioner Di Lembaga Pendidikan Islam",
            author = "Dr. Ahmad Zain Sarnoto",
            category = "Pendidikan"
        )
        reviewBook = BookCatalogRepository.enrichBook(initialBook)
        bindBook()
        findViewById<ImageView>(R.id.btnBackReview)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@ReviewActivity)
        }

        setupReviewForm()
        loadRealReviewsFromDatabase()

        bottomNav = findViewById(R.id.bottomNavigationReview)
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
    }

    private fun getBookFromIntent(): Book? = try {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            intent.getSerializableExtra(ReaderActivity.EXTRA_BOOK, Book::class.java)
        } else {
            @Suppress("DEPRECATION")
            intent.getSerializableExtra(ReaderActivity.EXTRA_BOOK) as? Book
        }
    } catch (exception: ClassCastException) {
        null
    }

    private fun bindBook() {
        findViewById<TextView>(R.id.tvReviewBookTitle)?.text = reviewBook.title
        findViewById<TextView>(R.id.tvReviewBookAuthor)?.text =
            listOfNotNull(
                reviewBook.author.takeIf { it.isNotBlank() },
                reviewBook.category?.takeIf { it.isNotBlank() }
            ).joinToString(" · ")

        findViewById<ImageView>(R.id.ivReviewBookCover)?.let { image ->
            BookCoverResolver.loadCover(image, reviewBook)
        }
    }

    private fun setupReviewForm() {
        val ratingBar = findViewById<RatingBar>(R.id.ratingBarUserReview)
        val ratingText = findViewById<TextView>(R.id.tvUserReviewRating)
        val reviewInput = findViewById<EditText>(R.id.etUserReview)
        val characterCount = findViewById<TextView>(R.id.tvReviewCharacterCount)
        val submitButton = findViewById<Button>(R.id.btnSubmitReview)

        // Rating bintang diatur 0 sejak awal sesuai permintaan pengguna
        ratingBar.rating = 0f
        BookActivityStore.getReview(this, reviewBook)?.let { savedReview ->
            if (savedReview.rating in 1..5) {
                ratingBar.rating = savedReview.rating.toFloat()
                reviewInput.setText(savedReview.text)
            }
        }

        fun updateRatingLabel(rating: Int) {
            ratingText.text = if (rating <= 0) {
                "Sentuh bintang di atas untuk memberi rating"
            } else {
                "★ $rating.0 / 5.0 · ${when (rating) {
                    5 -> "Sangat Memuaskan"
                    4 -> "Bagus & Bermanfaat"
                    3 -> "Cukup Baik"
                    2 -> "Perlu Peningkatan"
                    else -> "Kurang Memuaskan"
                }}"
            }
        }

        fun updateCharacterCount(length: Int) {
            characterCount.text = "$length / 500 karakter"
        }

        updateRatingLabel(ratingBar.rating.toInt())
        updateCharacterCount(reviewInput.text.length)
        ratingBar.setOnRatingBarChangeListener { _, rating, _ -> updateRatingLabel(rating.toInt()) }
        reviewInput.addTextChangedListener(object : android.text.TextWatcher {
            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) = Unit
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {
                updateCharacterCount(s?.length ?: 0)
            }
            override fun afterTextChanged(s: android.text.Editable?) = Unit
        })

        submitButton.setOnClickListener {
            val rating = ratingBar.rating.toInt()
            val text = reviewInput.text.toString().trim()
            when {
                rating !in 1..5 -> Toast.makeText(this, "Silakan pilih rating 1 sampai 5 bintang terlebih dahulu.", Toast.LENGTH_SHORT).show()
                text.isBlank() -> {
                    reviewInput.error = "Tulis ulasan terlebih dahulu."
                    reviewInput.requestFocus()
                }
                else -> submitReview(rating, text, reviewInput, submitButton)
            }
        }
    }

    private fun loadRealReviewsFromDatabase() {
        val tvScore = findViewById<TextView>(R.id.tvRatingScore)
        val tvCount = findViewById<TextView>(R.id.tvTotalReviewsCount)
        val tvStars = findViewById<TextView>(R.id.tvRatingStarsArtshow)

        val bookId = reviewBook.id.takeIf { it > 0 }
        RetrofitClient.apiService.getReviews(bookId, reviewBook.title).enqueue(object : Callback<ApiResponse<BookReviewListResponse>> {
            override fun onResponse(
                call: Call<ApiResponse<BookReviewListResponse>>,
                response: Response<ApiResponse<BookReviewListResponse>>
            ) {
                if (response.isSuccessful && response.body()?.success == true) {
                    val data = response.body()?.data
                    if (data != null) {
                        tvScore?.text = String.format(java.util.Locale.US, "%.1f", data.average_rating)
                        tvCount?.text = "${data.total_reviews} ulasan tersimpan"
                        val fullStars = data.average_rating.toInt().coerceIn(1, 5)
                        tvStars?.text = "★".repeat(fullStars) + "☆".repeat(5 - fullStars)
                    }
                }
            }

            override fun onFailure(call: Call<ApiResponse<BookReviewListResponse>>, t: Throwable) {
                // Fallback graceful
            }
        })
    }

    private fun submitReview(rating: Int, text: String, reviewInput: EditText, submitButton: Button) {
        submitButton.isEnabled = false
        submitButton.text = "Mengirim..."
        val submission = BookReviewSubmission(
            book_id = reviewBook.id.takeIf { it > 0 },
            book_title = reviewBook.title,
            reviewer_name = "Pembaca Pena Cendekia",
            rating = rating,
            review_text = text
        )
        RetrofitClient.apiService.submitBookReview(submission)
            .enqueue(object : Callback<ApiResponse<BookReviewRecord>> {
                override fun onResponse(
                    call: Call<ApiResponse<BookReviewRecord>>,
                    response: Response<ApiResponse<BookReviewRecord>>
                ) {
                    submitButton.isEnabled = true
                    submitButton.text = "Kirim ulasan"
                    val result = response.body()
                    if (response.isSuccessful && result?.success == true) {
                        BookActivityStore.saveReview(this@ReviewActivity, reviewBook, rating, text)
                        (getSystemService(Context.INPUT_METHOD_SERVICE) as? InputMethodManager)
                            ?.hideSoftInputFromWindow(reviewInput.windowToken, 0)
                        Toast.makeText(this@ReviewActivity, "Ulasan & rating berhasil disimpan di database!", Toast.LENGTH_LONG).show()
                        loadRealReviewsFromDatabase()
                        PageTransition.slideBackward(this@ReviewActivity)
                    } else {
                        Toast.makeText(
                            this@ReviewActivity,
                            result?.message ?: "Ulasan gagal dikirim (HTTP ${response.code()}).",
                            Toast.LENGTH_LONG
                        ).show()
                    }
                }

                override fun onFailure(call: Call<ApiResponse<BookReviewRecord>>, error: Throwable) {
                    submitButton.isEnabled = true
                    submitButton.text = "Kirim ulasan"
                    Toast.makeText(
                        this@ReviewActivity,
                        "Ulasan disimpan lokal: ${error.localizedMessage ?: "koneksi offline"}.",
                        Toast.LENGTH_SHORT
                    ).show()
                    BookActivityStore.saveReview(this@ReviewActivity, reviewBook, rating, text)
                    PageTransition.slideBackward(this@ReviewActivity)
                }
            })
    }

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.menu.findItem(R.id.nav_rak)?.isChecked = true
        }
    }
}
