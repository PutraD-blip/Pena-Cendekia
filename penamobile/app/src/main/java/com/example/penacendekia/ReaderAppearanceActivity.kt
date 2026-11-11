package com.example.penacendekia

import android.content.Context
import android.graphics.Bitmap
import android.graphics.Color
import android.graphics.pdf.PdfRenderer
import android.os.Build
import android.os.Bundle
import android.os.Handler
import android.os.Looper
import android.os.ParcelFileDescriptor
import android.view.View
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.ProgressBar
import android.widget.RelativeLayout
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
import java.io.File
import java.io.FileOutputStream
import java.util.concurrent.Executors

class ReaderAppearanceActivity : AppCompatActivity() {
    private lateinit var layoutAppearanceRoot: RelativeLayout
    private lateinit var toolbarAppearance: LinearLayout
    private lateinit var tvBookTitleAppearance: TextView
    private lateinit var tvSubtitleAppearance: TextView
    private lateinit var btnBookmarkAppearance: ImageView

    private lateinit var ivAppearancePdfPreview: ImageView
    private lateinit var pbPreviewLoading: ProgressBar
    private lateinit var cardPdfPreview: MaterialCardView

    private lateinit var cardThemeTerang: MaterialCardView
    private lateinit var cardThemeGelap: MaterialCardView
    private lateinit var cardThemeSepia: MaterialCardView

    private lateinit var readerBook: Book
    private var currentPage = 1
    private var currentTheme = "GELAP"

    private var pdfRenderer: PdfRenderer? = null
    private var pdfPfd: ParcelFileDescriptor? = null

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

        toolbarAppearance = findViewById(R.id.toolbarAppearance)
        toolbarAppearance.setPadding(toolbarAppearance.paddingLeft, statusBarHeight + extraTop, toolbarAppearance.paddingRight, toolbarAppearance.paddingBottom)
        ViewCompat.setOnApplyWindowInsetsListener(toolbarAppearance) { view, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            val topBar = if (systemBars.top > 0) systemBars.top else statusBarHeight
            view.setPadding(view.paddingLeft, topBar + extraTop, view.paddingRight, view.paddingBottom)
            insets
        }

        layoutAppearanceRoot = findViewById(R.id.layoutAppearanceRoot)
        tvBookTitleAppearance = findViewById(R.id.tvBookTitleAppearance)
        tvSubtitleAppearance = findViewById(R.id.tvSubtitleAppearance)
        btnBookmarkAppearance = findViewById(R.id.btnBookmarkAppearance)

        ivAppearancePdfPreview = findViewById(R.id.ivAppearancePdfPreview)
        pbPreviewLoading = findViewById(R.id.pbPreviewLoading)
        cardPdfPreview = findViewById(R.id.cardPdfPreview)

        cardThemeTerang = findViewById(R.id.cardThemeTerang)
        cardThemeGelap = findViewById(R.id.cardThemeGelap)
        cardThemeSepia = findViewById(R.id.cardThemeSepia)

        readerBook = getBookFromIntent() ?: Book(
            id = -1,
            title = "Asuhan Kebidanan Pada Kehamilan",
            author = "Dianita Primihastuti, S.ST., M.Keb., dkk."
        )
        currentPage = intent.getIntExtra(ReaderActivity.EXTRA_PAGE, 1).coerceAtLeast(1)
        tvBookTitleAppearance.text = readerBook.title

        // Baca preferensi tersimpan
        val prefs = getSharedPreferences(PREFS_READER, Context.MODE_PRIVATE)
        currentTheme = prefs.getString(KEY_READER_THEME, "GELAP") ?: "GELAP"

        // Setup Themes
        cardThemeTerang.setOnClickListener { applyTheme("TERANG") }
        cardThemeGelap.setOnClickListener { applyTheme("GELAP") }
        cardThemeSepia.setOnClickListener { applyTheme("SEPIA") }

        // Navigation
        findViewById<ImageView>(R.id.btnBackAppearance)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@ReaderAppearanceActivity)
        }
        findViewById<TextView>(R.id.btnDoneAppearance)?.setOnClickListener {
            Toast.makeText(this, "Pengaturan tampilan diterapkan!", Toast.LENGTH_SHORT).show()
            PageTransition.slideBackward(this)
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

        applyTheme(currentTheme, notifyUser = false)
        updateBookmarkButton()

        // Render pratinjau halaman PDF nyata
        loadPdfPagePreview()
    }

    private fun loadPdfPagePreview() {
        pbPreviewLoading.visibility = View.VISIBLE
        Executors.newSingleThreadExecutor().execute {
            val safeId = if (readerBook.id > 0) readerBook.id else readerBook.title.hashCode()
            val cacheFile = File(File(cacheDir, "ebooks"), "ebook_${safeId}.pdf")
            var targetFile: File? = null

            if (cacheFile.exists() && cacheFile.length() > 2048) {
                targetFile = cacheFile
            } else {
                val titleLower = readerBook.title.lowercase()
                val isAsuhanKebidanan = readerBook.id == 3 || readerBook.id == 1003 ||
                        titleLower.contains("asuhan") ||
                        titleLower.contains("kebidanan") ||
                        titleLower.contains("kehamilan")

                val assetName = if (isAsuhanKebidanan) "ebooks/asuhan_kebidanan.pdf" else "ebooks/ebook_${safeId}.pdf"
                try {
                    val input = assets.open(assetName)
                    val dir = File(cacheDir, "ebooks").apply { if (!exists()) mkdirs() }
                    val temp = File(dir, "preview_${safeId}.pdf")
                    FileOutputStream(temp).use { out -> input.copyTo(out) }
                    input.close()
                    if (temp.exists() && temp.length() > 2048) {
                        targetFile = temp
                    }
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }

            if (targetFile != null && targetFile.exists()) {
                try {
                    val pfd = ParcelFileDescriptor.open(targetFile, ParcelFileDescriptor.MODE_READ_ONLY)
                    pdfPfd = pfd
                    val renderer = PdfRenderer(pfd)
                    pdfRenderer = renderer

                    val pageIndex = (currentPage - 1).coerceIn(0, renderer.pageCount - 1)
                    val page = renderer.openPage(pageIndex)

                    val displayMetrics = resources.displayMetrics
                    val targetWidth = (displayMetrics.widthPixels - (48 * displayMetrics.density).toInt()).coerceAtLeast(300)
                    val scale = targetWidth.toFloat() / page.width.toFloat()
                    val targetHeight = (page.height * scale).toInt().coerceAtLeast(450)

                    val bitmap = Bitmap.createBitmap(targetWidth, targetHeight, Bitmap.Config.ARGB_8888)
                    val canvas = android.graphics.Canvas(bitmap)
                    canvas.drawColor(Color.WHITE)
                    page.render(bitmap, null, null, PdfRenderer.Page.RENDER_MODE_FOR_DISPLAY)
                    page.close()

                    Handler(Looper.getMainLooper()).post {
                        pbPreviewLoading.visibility = View.GONE
                        ivAppearancePdfPreview.setImageBitmap(bitmap)
                    }
                    return@execute
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }

            Handler(Looper.getMainLooper()).post {
                pbPreviewLoading.visibility = View.GONE
                ivAppearancePdfPreview.setImageResource(R.drawable.img_asuhan_kebidanan_pada_kehamilan)
            }
        }
    }

    private fun applyTheme(theme: String, notifyUser: Boolean = true) {
        currentTheme = theme
        val prefs = getSharedPreferences(PREFS_READER, Context.MODE_PRIVATE)
        prefs.edit().putString(KEY_READER_THEME, currentTheme).apply()

        val primaryGreen = ContextCompat.getColor(this, R.color.primary_green)
        val dividerColor = ContextCompat.getColor(this, R.color.divider_color)
        val selectedStroke = (2 * resources.displayMetrics.density).toInt()

        val background: Int
        val headingColor: Int
        val mutedColor: Int

        when (theme) {
            "TERANG" -> {
                background = ContextCompat.getColor(this, R.color.bg_cream)
                headingColor = ContextCompat.getColor(this, R.color.primary_green)
                mutedColor = ContextCompat.getColor(this, R.color.text_muted)
            }
            "GELAP" -> {
                background = Color.parseColor("#181E1B")
                headingColor = Color.parseColor("#A7D7C5")
                mutedColor = Color.parseColor("#8E9F97")
            }
            else -> { // "SEPIA"
                background = Color.parseColor("#F4E8D3")
                headingColor = Color.parseColor("#203C32")
                mutedColor = Color.parseColor("#7A6A55")
            }
        }

        layoutAppearanceRoot.setBackgroundColor(background)
        toolbarAppearance.setBackgroundColor(background)
        tvBookTitleAppearance.setTextColor(headingColor)
        tvSubtitleAppearance.setTextColor(mutedColor)
        findViewById<ImageView>(R.id.btnBackAppearance)?.setColorFilter(headingColor)
        btnBookmarkAppearance.setColorFilter(headingColor)

        val themeCards = listOf(
            "TERANG" to cardThemeTerang,
            "GELAP" to cardThemeGelap,
            "SEPIA" to cardThemeSepia
        )
        themeCards.forEach { (name, card) ->
            val selected = name == theme
            card.strokeColor = if (selected) primaryGreen else dividerColor
            card.strokeWidth = if (selected) selectedStroke else 1
        }
        findViewById<TextView>(R.id.tvLabelTerang)?.text = if (theme == "TERANG") "✓ Terang" else "Terang"
        findViewById<TextView>(R.id.tvLabelGelap)?.text = if (theme == "GELAP") "✓ Gelap" else "Gelap"
        findViewById<TextView>(R.id.tvLabelSepia)?.text = if (theme == "SEPIA") "✓ Sepia" else "Sepia"

        tvSubtitleAppearance.text = "Tema ${theme.lowercase().replaceFirstChar { it.uppercase() }}"

        if (notifyUser) {
            Toast.makeText(this, "Tema ${theme.lowercase().replaceFirstChar { it.uppercase() }} diterapkan", Toast.LENGTH_SHORT).show()
        }
    }

    private fun updateBookmarkButton() {
        val isSaved = BookActivityStore.getPageBookmark(this, readerBook)?.page == currentPage
        btnBookmarkAppearance.setImageResource(
            if (isSaved) R.drawable.ic_bookmark_check else R.drawable.ic_bookmark
        )
        btnBookmarkAppearance.contentDescription =
            if (isSaved) "Hapus penanda halaman" else "Simpan halaman"
    }

    private fun getBookFromIntent(): Book? = try {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            intent.getSerializableExtra(ReaderActivity.EXTRA_BOOK, Book::class.java)
        } else {
            @Suppress("DEPRECATION")
            intent.getSerializableExtra(ReaderActivity.EXTRA_BOOK) as? Book
        }
    } catch (_: ClassCastException) {
        null
    }

    override fun onDestroy() {
        super.onDestroy()
        try { pdfRenderer?.close() } catch (_: Exception) {}
        try { pdfPfd?.close() } catch (_: Exception) {}
    }
}
