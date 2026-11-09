package com.example.penacendekia

import android.content.Context
import android.graphics.Color
import android.os.Build
import android.os.Bundle
import android.util.TypedValue
import android.widget.ImageView
import android.widget.RelativeLayout
import android.widget.SeekBar
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

class ReaderAppearanceActivity : AppCompatActivity() {
    private lateinit var layoutAppearanceRoot: RelativeLayout
    private lateinit var tvBookTitleAppearance: TextView
    private lateinit var tvSubtitleAppearance: TextView
    private lateinit var tvChapterTitleAppearance: TextView
    private lateinit var tvParagraphAppearance: TextView
    private lateinit var tvFontSizeValue: TextView
    private lateinit var seekBarFontSize: SeekBar
    private lateinit var cardThemeTerang: MaterialCardView
    private lateinit var cardThemeGelap: MaterialCardView
    private lateinit var cardThemeSepia: MaterialCardView
    private lateinit var btnBookmarkAppearance: ImageView
    private lateinit var readerBook: Book
    private var currentPage = 42
    private var currentTheme = "SEPIA"
    private var currentFontSize = 16

    companion object {
        const val PREFS_READER = "reader_prefs"
        const val KEY_FONT_SIZE = "font_size"
        const val KEY_READER_THEME = "reader_theme"
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_reader_appearance)

        val resId = resources.getIdentifier("status_bar_height", "dimen", "android")
        val statusBarHeight = if (resId > 0) resources.getDimensionPixelSize(resId)
        else (24 * resources.displayMetrics.density).toInt()
        val extraTop = (24 * resources.displayMetrics.density).toInt()
        val toolbar = findViewById<android.widget.LinearLayout>(R.id.toolbarAppearance)
        toolbar.setPadding(toolbar.paddingLeft, statusBarHeight + extraTop, toolbar.paddingRight, toolbar.paddingBottom)
        ViewCompat.setOnApplyWindowInsetsListener(toolbar) { view, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            val topBar = if (systemBars.top > 0) systemBars.top else statusBarHeight
            view.setPadding(view.paddingLeft, topBar + extraTop, view.paddingRight, view.paddingBottom)
            insets
        }

        layoutAppearanceRoot = findViewById(R.id.layoutAppearanceRoot)
        tvBookTitleAppearance = findViewById(R.id.tvBookTitleAppearance)
        tvSubtitleAppearance = findViewById(R.id.tvSubtitleAppearance)
        tvChapterTitleAppearance = findViewById(R.id.tvChapterTitleAppearance)
        tvParagraphAppearance = findViewById(R.id.tvParagraphAppearance)
        tvFontSizeValue = findViewById(R.id.tvFontSizeValue)
        seekBarFontSize = findViewById(R.id.seekBarFontSize)
        cardThemeTerang = findViewById(R.id.cardThemeTerang)
        cardThemeGelap = findViewById(R.id.cardThemeGelap)
        cardThemeSepia = findViewById(R.id.cardThemeSepia)
        btnBookmarkAppearance = findViewById(R.id.btnBookmarkAppearance)

        readerBook = getBookFromIntent() ?: Book(
            id = -1,
            title = "Kepemimpinan Visioner Di Lembaga Pendidikan Islam",
            author = "Dr. Ahmad Zain Sarnoto"
        )
        currentPage = intent.getIntExtra(ReaderActivity.EXTRA_PAGE, 42).coerceAtLeast(1)
        tvBookTitleAppearance.text = readerBook.title

        // Read saved appearance preferences
        val prefs = getSharedPreferences(PREFS_READER, Context.MODE_PRIVATE)
        currentFontSize = prefs.getInt(KEY_FONT_SIZE, 16)
        currentTheme = prefs.getString(KEY_READER_THEME, "SEPIA") ?: "SEPIA"

        // Set initial font size UI
        val progress = (currentFontSize - 12).coerceIn(0, 20)
        seekBarFontSize.progress = progress
        tvFontSizeValue.text = "$currentFontSize px"
        tvParagraphAppearance.setTextSize(TypedValue.COMPLEX_UNIT_SP, currentFontSize.toFloat())
        tvChapterTitleAppearance.setTextSize(TypedValue.COMPLEX_UNIT_SP, (currentFontSize + 6).toFloat())

        findViewById<ImageView>(R.id.btnBackAppearance)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@ReaderAppearanceActivity)
        }
        btnBookmarkAppearance.setOnClickListener {
            val isSaved = BookActivityStore.togglePageBookmark(this, readerBook, currentPage)
            updateBookmarkButton()
            Toast.makeText(
                this,
                if (isSaved) "Halaman $currentPage disimpan." else "Penanda halaman dihapus.",
                Toast.LENGTH_SHORT
            ).show()
        }

        findViewById<TextView>(R.id.btnDoneAppearance)?.setOnClickListener {
            Toast.makeText(this, "Pengaturan tampilan disimpan!", Toast.LENGTH_SHORT).show()
            PageTransition.slideBackward(this)
        }

        seekBarFontSize.setOnSeekBarChangeListener(object : SeekBar.OnSeekBarChangeListener {
            override fun onProgressChanged(seekBar: SeekBar?, progress: Int, fromUser: Boolean) {
                currentFontSize = 12 + progress
                tvFontSizeValue.text = "$currentFontSize px"
                tvParagraphAppearance.setTextSize(TypedValue.COMPLEX_UNIT_SP, currentFontSize.toFloat())
                tvChapterTitleAppearance.setTextSize(TypedValue.COMPLEX_UNIT_SP, (currentFontSize + 6).toFloat())
                if (fromUser) {
                    prefs.edit().putInt(KEY_FONT_SIZE, currentFontSize).apply()
                }
            }

            override fun onStartTrackingTouch(seekBar: SeekBar?) = Unit
            override fun onStopTrackingTouch(seekBar: SeekBar?) = Unit
        })

        cardThemeTerang.setOnClickListener { applyTheme("TERANG") }
        cardThemeGelap.setOnClickListener { applyTheme("GELAP") }
        cardThemeSepia.setOnClickListener { applyTheme("SEPIA") }

        applyTheme(currentTheme, notifyUser = false)
    }

    override fun onResume() {
        super.onResume()
        if (::readerBook.isInitialized) updateBookmarkButton()
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

    private fun updateBookmarkButton() {
        val isSaved = BookActivityStore.getPageBookmark(this, readerBook)?.page == currentPage
        btnBookmarkAppearance.setImageResource(
            if (isSaved) R.drawable.ic_bookmark_check else R.drawable.ic_bookmark
        )
        btnBookmarkAppearance.contentDescription =
            if (isSaved) "Hapus penanda halaman" else "Simpan halaman"
    }

    private fun applyTheme(theme: String, notifyUser: Boolean = true) {
        currentTheme = theme
        val prefs = getSharedPreferences(PREFS_READER, Context.MODE_PRIVATE)
        prefs.edit().putString(KEY_READER_THEME, currentTheme).apply()

        val primaryGreen = ContextCompat.getColor(this, R.color.primary_green)
        val dividerColor = ContextCompat.getColor(this, R.color.divider_color)
        val selectedStroke = (2 * resources.displayMetrics.density).toInt()
        val background: Int
        val foreground: Int
        val muted: Int
        when (theme) {
            "TERANG" -> {
                background = Color.WHITE
                foreground = Color.parseColor("#203C32")
                muted = Color.parseColor("#68766E")
            }
            "GELAP" -> {
                background = Color.parseColor("#222B26")
                foreground = Color.WHITE
                muted = Color.parseColor("#A0AFA6")
            }
            else -> {
                background = Color.parseColor("#F4E8D3")
                foreground = Color.parseColor("#584A35")
                muted = Color.parseColor("#7A6A55")
            }
        }

        layoutAppearanceRoot.setBackgroundColor(background)
        tvBookTitleAppearance.setTextColor(foreground)
        tvSubtitleAppearance.setTextColor(muted)
        tvChapterTitleAppearance.setTextColor(foreground)
        tvParagraphAppearance.setTextColor(foreground)
        findViewById<TextView>(R.id.tvPageCenterAppearance)?.setTextColor(muted)
        findViewById<ImageView>(R.id.btnBackAppearance)?.setColorFilter(foreground)
        btnBookmarkAppearance.setColorFilter(foreground)

        val themeCards = listOf(
            "TERANG" to cardThemeTerang,
            "GELAP" to cardThemeGelap,
            "SEPIA" to cardThemeSepia
        )
        themeCards.forEach { (name, card) ->
            val selected = name == theme
            card.strokeColor = if (selected) primaryGreen else dividerColor
            card.strokeWidth = if (selected) selectedStroke else 0
        }
        findViewById<TextView>(R.id.tvLabelTerang)?.text = if (theme == "TERANG") "✓ Terang" else "Terang"
        findViewById<TextView>(R.id.tvLabelGelap)?.text = if (theme == "GELAP") "✓ Gelap" else "Gelap"
        findViewById<TextView>(R.id.tvLabelSepia)?.text = if (theme == "SEPIA") "✓ Sepia" else "Sepia"

        tvSubtitleAppearance.text = "Tema ${theme.lowercase().replaceFirstChar { it.uppercase() }} · Vertikal Scroll"

        if (notifyUser) {
            Toast.makeText(this, "Tema ${theme.lowercase().replaceFirstChar { it.uppercase() }} diterapkan", Toast.LENGTH_SHORT).show()
        }
    }
}
