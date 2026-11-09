package com.example.penacendekia

import android.graphics.Color
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.view.View
import android.widget.Button
import android.widget.EditText
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import android.widget.Toast
import androidx.activity.addCallback
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

import com.example.penacendekia.data.model.Book
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.card.MaterialCardView

class ReaderNotesActivity : AppCompatActivity() {
    private lateinit var readerBook: Book
    private var currentPage = 42

    private lateinit var cardHighlightedText: MaterialCardView
    private var isBookmarked: Boolean = true
    private var activeTool: String = "NONE" // "NONE", "SOROT", "CATATAN", "PENANDA"

    private lateinit var btnOptionHighlight: LinearLayout
    private lateinit var btnOptionNotes: LinearLayout
    private lateinit var btnOptionBookmark: LinearLayout

    private lateinit var layoutColorPicker: MaterialCardView
    private lateinit var cardNoteInput: MaterialCardView

    private lateinit var cardColorYellow: MaterialCardView
    private lateinit var cardColorGreen: MaterialCardView
    private lateinit var cardColorBlue: MaterialCardView
    private lateinit var cardColorPink: MaterialCardView

    private lateinit var icCheckYellow: ImageView
    private lateinit var icCheckGreen: ImageView
    private lateinit var icCheckBlue: ImageView
    private lateinit var icCheckPink: ImageView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_reader_notes)

        // Window insets padding for toolbar
        val resId = resources.getIdentifier("status_bar_height", "dimen", "android")
        val statusBarHeight = if (resId > 0) resources.getDimensionPixelSize(resId) else (24 * resources.displayMetrics.density).toInt()
        val extraTop = (24 * resources.displayMetrics.density).toInt()
        val toolbar = findViewById<LinearLayout>(R.id.toolbarNotes)
        toolbar?.setPadding(toolbar.paddingLeft, statusBarHeight + extraTop, toolbar.paddingRight, toolbar.paddingBottom)

        ViewCompat.setOnApplyWindowInsetsListener(toolbar) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            val topBar = if (systemBars.top > 0) systemBars.top else statusBarHeight
            v.setPadding(v.paddingLeft, topBar + extraTop, v.paddingRight, v.paddingBottom)
            insets
        }

        readerBook = getBookFromIntent() ?: Book(
            id = -1,
            title = "Kepemimpinan Visioner Di Lembaga Pendidikan Islam",
            author = "Dr. Ahmad Zain Sarnoto"
        )
        currentPage = intent.getIntExtra(ReaderActivity.EXTRA_PAGE, 42).coerceAtLeast(1)
        findViewById<TextView>(R.id.tvBookTitleNotes)?.text = readerBook.title
        updateBookmarkStatus()
        findViewById<ImageView>(R.id.btnBookmarkNotes)?.setOnClickListener {
            toggleBookmark()
        }
        
        // View Bindings
        cardHighlightedText = findViewById(R.id.cardHighlightedText)

        btnOptionHighlight = findViewById(R.id.btnOptionHighlight)
        btnOptionNotes = findViewById(R.id.btnOptionNotes)
        btnOptionBookmark = findViewById(R.id.btnOptionBookmark)

        layoutColorPicker = findViewById(R.id.layoutColorPicker)
        cardNoteInput = findViewById(R.id.cardNoteInput)

        cardColorYellow = findViewById(R.id.cardColorYellow)
        cardColorGreen = findViewById(R.id.cardColorGreen)
        cardColorBlue = findViewById(R.id.cardColorBlue)
        cardColorPink = findViewById(R.id.cardColorPink)

        icCheckYellow = findViewById(R.id.icCheckYellow)
        icCheckGreen = findViewById(R.id.icCheckGreen)
        icCheckBlue = findViewById(R.id.icCheckBlue)
        icCheckPink = findViewById(R.id.icCheckPink)

        // Header Back button action
        findViewById<ImageView>(R.id.btnBackNotes)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@ReaderNotesActivity)
        }

        // Header Bookmark Toggle
        val btnBookmarkHeader = findViewById<ImageView>(R.id.btnBookmarkNotes)
        btnBookmarkHeader?.setOnClickListener { toggleBookmark() }

        // 3 Buttons in Dark Green Bottom Bar
        btnOptionHighlight.setOnClickListener {
            toggleTool("SOROT")
        }

        btnOptionNotes.setOnClickListener {
            toggleTool("CATATAN")
        }

        btnOptionBookmark.setOnClickListener {
            toggleTool("PENANDA")
            toggleBookmark()
        }

        // Color Picker Options
        cardColorYellow.setOnClickListener {
            selectHighlightColor("#F8E79F", cardColorYellow, icCheckYellow, "Kuning")
        }

        cardColorGreen.setOnClickListener {
            selectHighlightColor("#BCE3C5", cardColorGreen, icCheckGreen, "Hijau")
        }

        cardColorBlue.setOnClickListener {
            selectHighlightColor("#B8D8E9", cardColorBlue, icCheckBlue, "Biru")
        }

        cardColorPink.setOnClickListener {
            selectHighlightColor("#ECC2C4", cardColorPink, icCheckPink, "Merah Muda")
        }

        // Save Note Button action
        val etNote = findViewById<EditText>(R.id.etNoteContent)
        val btnSave = findViewById<Button>(R.id.btnSaveNote)
        btnSave?.setOnClickListener {
            val noteText = etNote?.text?.toString()?.trim() ?: ""
            if (noteText.isNotEmpty()) {
                btnSave.text = "✓ Catatan Tersimpan"
                btnSave.setBackgroundColor(Color.parseColor("#2E7D32"))
                Toast.makeText(this, "Catatan berhasil disimpan pada Halaman 42!", Toast.LENGTH_SHORT).show()

                // Smoothly hide note input after 1 second so user returns to full text view
                Handler(Looper.getMainLooper()).postDelayed({
                    toggleTool("NONE")
                    btnSave.text = "Simpan catatan"
                    btnSave.setBackgroundColor(ContextCompat.getColor(this, R.color.primary_green))
                }, 1200)
            } else {
                Toast.makeText(this, "Tulis catatan terlebih dahulu", Toast.LENGTH_SHORT).show()
            }
        }

        // Tapping quote box toggles color picker
        cardHighlightedText.setOnClickListener {
            toggleTool("SOROT")
        }

        // Bookmark notice tag
        findViewById<LinearLayout>(R.id.layoutBookmarkNotice)?.setOnClickListener {
            showBookmarksDialog()
        }

        // Initial state: FULL TEXT view (No panels shown)
        toggleTool("NONE")
        selectHighlightColor("#F8E79F", cardColorYellow, icCheckYellow, null)
    }

    private fun toggleTool(tool: String) {
        val primaryGreen = ContextCompat.getColor(this, R.color.primary_green)
        val colorWhite = Color.WHITE

        val ivHighlight = findViewById<ImageView>(R.id.ivOptionHighlight)
        val tvHighlight = findViewById<TextView>(R.id.tvOptionHighlight)

        val ivNotes = findViewById<ImageView>(R.id.ivOptionNotes)
        val tvNotes = findViewById<TextView>(R.id.tvOptionNotes)

        val ivBookmark = findViewById<ImageView>(R.id.ivOptionBookmark)
        val tvBookmark = findViewById<TextView>(R.id.tvOptionBookmark)

        // Reset all buttons to default unselected (Dark green bar)
        btnOptionHighlight.setBackgroundResource(0)
        btnOptionHighlight.backgroundTintList = null
        ivHighlight?.setColorFilter(colorWhite)
        tvHighlight?.setTextColor(colorWhite)

        btnOptionNotes.setBackgroundResource(0)
        btnOptionNotes.backgroundTintList = null
        ivNotes?.setColorFilter(colorWhite)
        tvNotes?.setTextColor(colorWhite)

        btnOptionBookmark.setBackgroundResource(0)
        btnOptionBookmark.backgroundTintList = null
        ivBookmark?.setColorFilter(colorWhite)
        tvBookmark?.setTextColor(colorWhite)

        if (activeTool == tool && tool != "NONE") {
            // If already open, close it (toggle off to return to full text view)
            activeTool = "NONE"
            layoutColorPicker.visibility = View.GONE
            cardNoteInput.visibility = View.GONE
            return
        }

        activeTool = tool

        when (tool) {
            "SOROT" -> {
                setToolButtonSelected(btnOptionHighlight)
                ivHighlight?.setColorFilter(primaryGreen)
                tvHighlight?.setTextColor(primaryGreen)

                layoutColorPicker.visibility = View.VISIBLE
                cardNoteInput.visibility = View.GONE

                Toast.makeText(this, "Pilih warna untuk menyorot kutipan", Toast.LENGTH_SHORT).show()
            }
            "CATATAN" -> {
                setToolButtonSelected(btnOptionNotes)
                ivNotes?.setColorFilter(primaryGreen)
                tvNotes?.setTextColor(primaryGreen)

                layoutColorPicker.visibility = View.GONE
                cardNoteInput.visibility = View.VISIBLE

                findViewById<EditText>(R.id.etNoteContent)?.requestFocus()
                Toast.makeText(this, "Tulis catatan kecil Anda", Toast.LENGTH_SHORT).show()
            }
            "PENANDA" -> {
                setToolButtonSelected(btnOptionBookmark)
                ivBookmark?.setColorFilter(primaryGreen)
                tvBookmark?.setTextColor(primaryGreen)

                layoutColorPicker.visibility = View.GONE
                cardNoteInput.visibility = View.GONE
            }
            "NONE" -> {
                layoutColorPicker.visibility = View.GONE
                cardNoteInput.visibility = View.GONE
            }
        }
    }

    private fun setToolButtonSelected(button: LinearLayout) {
        button.setBackgroundResource(R.drawable.bg_chip_unselected)
        button.backgroundTintList = android.content.res.ColorStateList.valueOf(Color.parseColor("#E8F2EC"))
    }

    private fun selectHighlightColor(colorHex: String, selectedCard: MaterialCardView, selectedCheck: ImageView, name: String?) {
        val primaryGreen = ContextCompat.getColor(this, R.color.primary_green)
        val strokeWidthPx = (2 * resources.displayMetrics.density).toInt()

        // Hide checkmarks and reset borders
        icCheckYellow.visibility = View.GONE
        icCheckGreen.visibility = View.GONE
        icCheckBlue.visibility = View.GONE
        icCheckPink.visibility = View.GONE

        cardColorYellow.strokeWidth = 0
        cardColorGreen.strokeWidth = 0
        cardColorBlue.strokeWidth = 0
        cardColorPink.strokeWidth = 0

        cardColorYellow.strokeColor = Color.TRANSPARENT
        cardColorGreen.strokeColor = Color.TRANSPARENT
        cardColorBlue.strokeColor = Color.TRANSPARENT
        cardColorPink.strokeColor = Color.TRANSPARENT

        // Show checkmark and stroke on selected
        selectedCheck.visibility = View.VISIBLE
        selectedCard.strokeColor = primaryGreen
        selectedCard.strokeWidth = strokeWidthPx

        // Change quote box background color
        cardHighlightedText.setCardBackgroundColor(Color.parseColor(colorHex))

        if (name != null) {
            Toast.makeText(this, "Warna sorotan: $name", Toast.LENGTH_SHORT).show()
        }
    }

    private fun showBookmarksDialog() {
        val bookmarks = BookActivityStore.getBookmarks(this)
            .filter { it.book.id == readerBook.id || it.book.title == readerBook.title }
        val labels = bookmarks.map { "Halaman ${it.page} · ${it.book.title}" }.toTypedArray()
        if (labels.isEmpty()) {
            Toast.makeText(this, "Belum ada penanda halaman tersimpan.", Toast.LENGTH_SHORT).show()
            return
        }
        android.app.AlertDialog.Builder(this)
            .setTitle("Penanda halaman")
            .setItems(labels) { _, index ->
                currentPage = bookmarks[index].page
                updateBookmarkStatus()
                Toast.makeText(this, "Beralih ke halaman $currentPage", Toast.LENGTH_SHORT).show()
            }
            .setNegativeButton("Tutup", null)
            .show()
    }

    override fun onResume() {
        super.onResume()
        if (::readerBook.isInitialized) updateBookmarkStatus()
    }

    private fun getBookFromIntent(): Book? = try {
        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.TIRAMISU) {
            intent.getSerializableExtra(ReaderActivity.EXTRA_BOOK, Book::class.java)
        } else {
            @Suppress("DEPRECATION")
            intent.getSerializableExtra(ReaderActivity.EXTRA_BOOK) as? Book
        }
    } catch (exception: ClassCastException) {
        null
    }

    private fun updateBookmarkStatus() {
        val isSaved = BookActivityStore.getPageBookmark(this, readerBook)?.page == currentPage
        findViewById<ImageView>(R.id.btnBookmarkNotes)?.apply {
            setImageResource(if (isSaved) R.drawable.ic_bookmark_check else R.drawable.ic_bookmark)
            contentDescription = if (isSaved) "Hapus penanda halaman" else "Simpan halaman"
        }
        findViewById<ImageView>(R.id.ivOptionBookmark)?.setImageResource(
            if (isSaved) R.drawable.ic_bookmark_check else R.drawable.ic_bookmark
        )
        findViewById<TextView>(R.id.tvOptionBookmark)?.text = if (isSaved) "Ditandai" else "Penanda"
    }

    private fun toggleBookmark() {
        val isSaved = BookActivityStore.togglePageBookmark(this, readerBook, currentPage)
        updateBookmarkStatus()
        Toast.makeText(
            this,
            if (isSaved) "Halaman $currentPage disimpan." else "Penanda halaman dihapus.",
            Toast.LENGTH_SHORT
        ).show()
    }
}
