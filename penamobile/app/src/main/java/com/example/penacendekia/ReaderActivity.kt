package com.example.penacendekia

import android.content.Context
import android.content.Intent
import android.graphics.Color
import android.net.Uri
import android.os.Build
import android.os.Bundle
import android.util.TypedValue
import android.view.View
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.ProgressBar
import android.widget.TextView
import android.widget.Toast
import androidx.activity.addCallback
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import androidx.core.widget.NestedScrollView
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.repository.BookCatalogRepository
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.card.MaterialCardView

class ReaderActivity : AppCompatActivity() {
    private lateinit var readerBook: Book
    private var currentPage = 1
    private var totalPages = 216

    private lateinit var layoutReaderRoot: View
    private lateinit var toolbarReader: LinearLayout
    private lateinit var layoutReaderBottomControls: LinearLayout
    private lateinit var scrollViewReader: NestedScrollView
    private lateinit var tvProgress: TextView
    private lateinit var tvReaderScrollMode: TextView
    private lateinit var tvChapterNum: TextView
    private lateinit var tvChapterTitle: TextView
    private lateinit var tvParagraph1: TextView
    private lateinit var tvParagraph2: TextView
    private lateinit var tvParagraph3: TextView
    private lateinit var tvReaderFooter: TextView
    private lateinit var progressBar: ProgressBar
    private lateinit var cardFileInfo: MaterialCardView
    private lateinit var tvFileInfoBadge: TextView
    private lateinit var tvFileInfoDetail: TextView
    private lateinit var btnOpenOriginalDoc: TextView
    private lateinit var btnBookmarkReader: ImageView
    private lateinit var btnBackReader: ImageView
    private lateinit var tvReaderBookTitle: TextView
    private lateinit var tvReaderBookAuthor: TextView
    private lateinit var dividerReaderBottom: View

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_reader)

        // Bind all UI Views
        layoutReaderRoot = findViewById(R.id.layoutReaderRoot)
        toolbarReader = findViewById(R.id.toolbarReader)
        layoutReaderBottomControls = findViewById(R.id.layoutReaderBottomControls)
        scrollViewReader = findViewById(R.id.scrollViewReader)
        tvProgress = findViewById(R.id.tvReaderProgress)
        tvReaderScrollMode = findViewById(R.id.tvReaderScrollMode)
        tvChapterNum = findViewById(R.id.tvChapterNum)
        tvChapterTitle = findViewById(R.id.tvChapterTitle)
        tvParagraph1 = findViewById(R.id.tvParagraph1)
        tvParagraph2 = findViewById(R.id.tvParagraph2)
        tvParagraph3 = findViewById(R.id.tvParagraph3)
        tvReaderFooter = findViewById(R.id.tvReaderFooter)
        progressBar = findViewById(R.id.readerProgressBar)
        cardFileInfo = findViewById(R.id.cardFileInfo)
        tvFileInfoBadge = findViewById(R.id.tvFileInfoBadge)
        tvFileInfoDetail = findViewById(R.id.tvFileInfoDetail)
        btnOpenOriginalDoc = findViewById(R.id.btnOpenOriginalDoc)
        btnBookmarkReader = findViewById(R.id.btnBookmarkReader)
        btnBackReader = findViewById(R.id.btnBackReader)
        tvReaderBookTitle = findViewById(R.id.tvReaderBookTitle)
        tvReaderBookAuthor = findViewById(R.id.tvReaderBookAuthor)
        dividerReaderBottom = findViewById(R.id.dividerReaderBottom)

        // Edge-to-edge window insets
        val resId = resources.getIdentifier("status_bar_height", "dimen", "android")
        val statusBarHeight = if (resId > 0) resources.getDimensionPixelSize(resId)
        else (24 * resources.displayMetrics.density).toInt()
        val extraTop = (28 * resources.displayMetrics.density).toInt()

        toolbarReader.setPadding(toolbarReader.paddingLeft, statusBarHeight + extraTop, toolbarReader.paddingRight, toolbarReader.paddingBottom)
        ViewCompat.setOnApplyWindowInsetsListener(toolbarReader) { view, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            val topBar = if (systemBars.top > 0) systemBars.top else statusBarHeight
            view.setPadding(view.paddingLeft, topBar + extraTop, view.paddingRight, view.paddingBottom)
            insets
        }
        ViewCompat.setOnApplyWindowInsetsListener(layoutReaderBottomControls) { view, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            view.setPadding(view.paddingLeft, view.paddingTop, view.paddingRight, systemBars.bottom + 16)
            insets
        }

        // Load Book model from Intent
        val initialBook = getBookFromIntent() ?: Book(
            id = -1,
            title = "Digitalisasi Pendidikan Islam",
            author = "Dr. Hj. Siti Yumnah, M.Pd.I",
            category = "Pendidikan",
            pages = "216 hlm"
        )
        readerBook = BookCatalogRepository.enrichBook(initialBook)
        totalPages = readerBook.pages?.filter(Char::isDigit)?.toIntOrNull()?.takeIf { it > 0 } ?: 216
        currentPage = intent.getIntExtra(EXTRA_PAGE, 1)

        // Bind book content & admin file integration
        bindBookData()

        // Sync progress bar directly with scroll position
        scrollViewReader.setOnScrollChangeListener(NestedScrollView.OnScrollChangeListener { v, _, scrollY, _, _ ->
            val childHeight = v.getChildAt(0)?.measuredHeight ?: 0
            val scrollableDistance = childHeight - v.height
            val progress = if (scrollableDistance > 0) {
                ((scrollY.toFloat() / scrollableDistance.toFloat()) * 100).toInt().coerceIn(0, 100)
            } else {
                0
            }
            progressBar.progress = progress
            tvProgress.text = "$progress% dibaca"
        })

        // Action buttons
        btnBackReader.setOnClickListener { PageTransition.slideBackward(this) }
        onBackPressedDispatcher.addCallback(this) { PageTransition.slideBackward(this@ReaderActivity) }
        btnBookmarkReader.setOnClickListener {
            val saved = BookActivityStore.togglePageBookmark(this, readerBook, currentPage)
            updateBookmarkButton()
            Toast.makeText(
                this,
                if (saved) "Halaman bacaan disimpan di Rak Saya." else "Penanda halaman dihapus.",
                Toast.LENGTH_SHORT
            ).show()
        }

        findViewById<LinearLayout>(R.id.btnAppearance)?.setOnClickListener {
            PageTransition.slideForward(this, createReaderToolIntent(ReaderAppearanceActivity::class.java))
        }
        findViewById<LinearLayout>(R.id.btnNotes)?.setOnClickListener {
            PageTransition.slideForward(this, createReaderToolIntent(ReaderNotesActivity::class.java))
        }
        findViewById<LinearLayout>(R.id.btnToc)?.setOnClickListener {
            showTableOfContentsDialog()
        }

        updateBookmarkButton()
    }

    override fun onResume() {
        super.onResume()
        if (::readerBook.isInitialized) updateBookmarkButton()
        // Apply font size and screen theme dynamically from SharedPreferences
        applyAppearanceSettings()
    }

    private fun bindBookData() {
        tvReaderBookTitle.text = readerBook.title
        tvReaderBookAuthor.text = readerBook.author

        // Admin uploaded file integration
        val categoryText = readerBook.category ?: "Katalog E-Book"
        val sizeText = readerBook.size ?: "PDF Dokumen Resmi"
        val pagesText = readerBook.pages ?: "$totalPages Halaman"
        tvFileInfoBadge.text = "E-Book Terbitan Resmi: $categoryText"
        tvFileInfoDetail.text = "Spesifikasi: $sizeText • $pagesText • Terbitan Pena Cendekia"

        val docUrl = readerBook.file_url ?: readerBook.download_url ?: readerBook.sample_url
        if (!docUrl.isNullOrBlank()) {
            btnOpenOriginalDoc.visibility = View.VISIBLE
            btnOpenOriginalDoc.setOnClickListener {
                try {
                    val browserIntent = Intent(Intent.ACTION_VIEW, Uri.parse(docUrl))
                    startActivity(browserIntent)
                } catch (e: Exception) {
                    Toast.makeText(this, "Tidak dapat membuka tautan berkas: ${e.message}", Toast.LENGTH_SHORT).show()
                }
            }
        } else {
            btnOpenOriginalDoc.visibility = View.GONE
        }

        // Subtitle / Chapter
        if (!readerBook.subtitle.isNullOrBlank()) {
            tvChapterTitle.text = readerBook.subtitle
        } else {
            tvChapterTitle.text = readerBook.title
        }

        // Synopsis content from admin upload
        val synopsis = readerBook.synopsis
        if (!synopsis.isNullOrBlank()) {
            val sentences = synopsis.split(Regex("(?<=[.!?])\\s+"))
            if (sentences.size >= 4) {
                val mid1 = sentences.size / 3
                val mid2 = (sentences.size * 2) / 3
                tvParagraph1.text = sentences.subList(0, mid1).joinToString(" ")
                tvParagraph2.text = sentences.subList(mid1, mid2).joinToString(" ")
                tvParagraph3.text = sentences.subList(mid2, sentences.size).joinToString(" ")
            } else if (sentences.size >= 2) {
                val mid = sentences.size / 2
                tvParagraph1.text = sentences.subList(0, mid).joinToString(" ")
                tvParagraph2.text = sentences.subList(mid, sentences.size).joinToString(" ")
            } else {
                tvParagraph1.text = synopsis
            }
        }
    }

    private fun applyAppearanceSettings() {
        val prefs = getSharedPreferences(ReaderAppearanceActivity.PREFS_READER, Context.MODE_PRIVATE)
        val fontSize = prefs.getInt(ReaderAppearanceActivity.KEY_FONT_SIZE, 16)
        val theme = prefs.getString(ReaderAppearanceActivity.KEY_READER_THEME, "SEPIA") ?: "SEPIA"

        // Apply font sizes
        tvParagraph1.setTextSize(TypedValue.COMPLEX_UNIT_SP, fontSize.toFloat())
        tvParagraph2.setTextSize(TypedValue.COMPLEX_UNIT_SP, fontSize.toFloat())
        tvParagraph3.setTextSize(TypedValue.COMPLEX_UNIT_SP, fontSize.toFloat())
        tvChapterTitle.setTextSize(TypedValue.COMPLEX_UNIT_SP, (fontSize + 6).toFloat())

        // Apply color theme
        val backgroundColor: Int
        val headingColor: Int
        val textColor: Int
        val mutedColor: Int
        val cardBg: Int
        val dividerColor: Int
        val progressTint: Int

        when (theme) {
            "TERANG" -> {
                backgroundColor = ContextCompat.getColor(this, R.color.bg_cream)
                headingColor = ContextCompat.getColor(this, R.color.primary_green)
                textColor = ContextCompat.getColor(this, R.color.text_dark)
                mutedColor = ContextCompat.getColor(this, R.color.text_muted)
                cardBg = Color.parseColor("#E8F2EC")
                dividerColor = ContextCompat.getColor(this, R.color.divider_color)
                progressTint = ContextCompat.getColor(this, R.color.primary_green)
            }
            "GELAP" -> {
                backgroundColor = Color.parseColor("#181E1B")
                headingColor = Color.parseColor("#A7D7C5")
                textColor = Color.parseColor("#D1D5DB")
                mutedColor = Color.parseColor("#8E9F97")
                cardBg = Color.parseColor("#242E29")
                dividerColor = Color.parseColor("#2E3A34")
                progressTint = Color.parseColor("#4CAF50")
            }
            else -> { // "SEPIA"
                backgroundColor = Color.parseColor("#F4E8D3")
                headingColor = Color.parseColor("#203C32")
                textColor = Color.parseColor("#433422")
                mutedColor = Color.parseColor("#7A6A55")
                cardBg = Color.parseColor("#EBDDC3")
                dividerColor = Color.parseColor("#DDD0B8")
                progressTint = Color.parseColor("#203C32")
            }
        }

        layoutReaderRoot.setBackgroundColor(backgroundColor)
        toolbarReader.setBackgroundColor(backgroundColor)
        layoutReaderBottomControls.setBackgroundColor(backgroundColor)

        tvReaderBookTitle.setTextColor(headingColor)
        tvReaderBookAuthor.setTextColor(mutedColor)
        btnBackReader.setColorFilter(headingColor)
        btnBookmarkReader.setColorFilter(headingColor)

        tvChapterNum.setTextColor(mutedColor)
        tvChapterTitle.setTextColor(headingColor)
        tvParagraph1.setTextColor(textColor)
        tvParagraph2.setTextColor(textColor)
        tvParagraph3.setTextColor(textColor)
        tvReaderFooter.setTextColor(mutedColor)

        cardFileInfo.setCardBackgroundColor(cardBg)
        tvFileInfoBadge.setTextColor(headingColor)
        tvFileInfoDetail.setTextColor(mutedColor)
        btnOpenOriginalDoc.setTextColor(headingColor)

        tvReaderScrollMode.setTextColor(mutedColor)
        tvProgress.setTextColor(headingColor)
        progressBar.progressTintList = android.content.res.ColorStateList.valueOf(progressTint)
        dividerReaderBottom.setBackgroundColor(dividerColor)

        findViewById<ImageView>(R.id.ivTocIcon)?.setColorFilter(headingColor)
        findViewById<TextView>(R.id.tvTocLabel)?.setTextColor(headingColor)
        findViewById<ImageView>(R.id.ivAppearanceIcon)?.setColorFilter(headingColor)
        findViewById<TextView>(R.id.tvAppearanceLabel)?.setTextColor(headingColor)
        findViewById<ImageView>(R.id.ivNotesIcon)?.setColorFilter(headingColor)
        findViewById<TextView>(R.id.tvNotesLabel)?.setTextColor(headingColor)
    }

    private fun getBookFromIntent(): Book? = try {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            intent.getSerializableExtra(EXTRA_BOOK, Book::class.java)
        } else {
            @Suppress("DEPRECATION")
            intent.getSerializableExtra(EXTRA_BOOK) as? Book
        }
    } catch (exception: ClassCastException) {
        null
    }

    private fun createReaderToolIntent(activity: Class<*>) =
        Intent(this, activity)
            .putExtra(EXTRA_BOOK, readerBook)
            .putExtra(EXTRA_PAGE, currentPage)

    private fun updateBookmarkButton() {
        val isBookmarked = BookActivityStore.getPageBookmark(this, readerBook) != null
        btnBookmarkReader.apply {
            setImageResource(if (isBookmarked) R.drawable.ic_bookmark_check else R.drawable.ic_bookmark)
            contentDescription = if (isBookmarked) "Hapus penanda halaman" else "Simpan halaman"
        }
    }

    private fun showTableOfContentsDialog() {
        val chapters = arrayOf(
            "BAB 1 · Pengantar & Landasan Teori",
            "BAB 2 · Metodologi dan Pendekatan Kajian",
            "BAB 3 · Analisis Temuan dan Diskusi Akademik",
            "BAB 4 · Implementasi Praktis & Best Practice",
            "BAB 5 · Kesimpulan, Refleksi, dan Rekomendasi"
        )
        android.app.AlertDialog.Builder(this)
            .setTitle("${readerBook.title} · Daftar Isi")
            .setItems(chapters) { _, index ->
                tvChapterNum.text = "BAB ${index + 1}"
                val titlePart = chapters[index].substringAfter(" · ")
                tvChapterTitle.text = titlePart
                // Scroll smoothly to relevant section
                val targetY = when (index) {
                    0 -> 0
                    1 -> (tvParagraph1.top)
                    2 -> (tvParagraph2.top)
                    else -> (tvParagraph3.top)
                }
                scrollViewReader.smoothScrollTo(0, targetY)
                Toast.makeText(this, "Beralih ke BAB ${index + 1}", Toast.LENGTH_SHORT).show()
            }
            .setNegativeButton("Tutup", null)
            .show()
    }

    companion object {
        const val EXTRA_BOOK = "BOOK_OBJECT"
        const val EXTRA_PAGE = "BOOKMARK_PAGE"
    }
}
