package com.example.penacendekia

import android.content.Context
import android.content.Intent
import android.content.res.ColorStateList
import android.graphics.Color
import android.graphics.RectF
import android.graphics.pdf.PdfRenderer
import android.os.Build
import android.os.Bundle
import android.os.ParcelFileDescriptor
import android.util.Log
import android.util.TypedValue
import android.view.View
import android.view.ViewGroup
import android.widget.EditText
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.ProgressBar
import android.widget.TextView
import android.widget.Toast
import androidx.activity.addCallback
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.core.content.res.ResourcesCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import androidx.core.widget.NestedScrollView
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.example.penacendekia.data.api.RetrofitClient
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.repository.BookCatalogRepository
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.bottomsheet.BottomSheetDialog
import com.google.android.material.button.MaterialButton
import com.google.android.material.card.MaterialCardView
import okhttp3.Request
import java.io.File
import java.io.FileOutputStream
import java.io.InputStream
import java.util.UUID
import java.util.concurrent.Executors

class ReaderActivity : AppCompatActivity() {
    private lateinit var readerBook: Book
    private var currentPage = 1
    private var totalPages = 216

    private var rvPdfPages: RecyclerView? = null
    private var layoutPdfLoading: View? = null
    private var tvPdfLoadingTitle: TextView? = null
    private var tvPdfLoadingSub: TextView? = null
    private var pdfRenderer: PdfRenderer? = null
    private var pdfFileDescriptor: ParcelFileDescriptor? = null
    private var pdfAdapter: PdfPagesAdapter? = null

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
        btnBookmarkReader = findViewById(R.id.btnBookmarkReader)
        btnBackReader = findViewById(R.id.btnBackReader)
        tvReaderBookTitle = findViewById(R.id.tvReaderBookTitle)
        tvReaderBookAuthor = findViewById(R.id.tvReaderBookAuthor)
        dividerReaderBottom = findViewById(R.id.dividerReaderBottom)

        rvPdfPages = findViewById(R.id.rvPdfPages)
        layoutPdfLoading = findViewById(R.id.layoutPdfLoading)
        tvPdfLoadingTitle = findViewById(R.id.tvPdfLoadingTitle)
        tvPdfLoadingSub = findViewById(R.id.tvPdfLoadingSub)

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

        // Resolusi halaman terakhir / penanda buku
        val savedBookmarkPage = BookActivityStore.getPageBookmark(this, readerBook)?.page
        val savedSessionPage = BookActivityStore.getActiveSessions(this)
            .find { BookActivityStore.bookKey(it.book) == BookActivityStore.bookKey(readerBook) }
            ?.currentPage

        val requestedPage = intent.getIntExtra(EXTRA_PAGE, -1)
        currentPage = when {
            requestedPage > 0 -> requestedPage
            savedBookmarkPage != null && savedBookmarkPage > 0 -> savedBookmarkPage
            savedSessionPage != null && savedSessionPage > 0 -> savedSessionPage
            else -> 1
        }

        // Bind book content & start in-app PDF manuscript reader
        bindBookData()

        // Catat sesi membaca aktif untuk pengguna saat ini
        BookActivityStore.recordReadingSession(this, readerBook, currentPage, totalPages)

        // Fallback text view scroll listener
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
            val pageEstimate = ((progress.toFloat() / 100f) * totalPages).toInt().coerceIn(1, totalPages)
            BookActivityStore.recordReadingSession(this, readerBook, pageEstimate, totalPages, progress)
        })

        // Action buttons
        btnBackReader.setOnClickListener { PageTransition.slideBackward(this) }
        onBackPressedDispatcher.addCallback(this) { PageTransition.slideBackward(this@ReaderActivity) }
        
        btnBookmarkReader.setOnClickListener {
            val saved = BookActivityStore.togglePageBookmark(this, readerBook, currentPage)
            // Sekaligus catat halaman ini sebagai sesi terakhir membaca agar "Lanjut Membaca" langsung ke sini
            BookActivityStore.recordReadingSession(this, readerBook, currentPage, totalPages)
            updateBookmarkButton()
            Toast.makeText(
                this,
                if (saved) "Halaman $currentPage ditandai. Anda dapat langsung 'Lanjut membaca' dari Rak Buku." else "Penanda halaman dihapus.",
                Toast.LENGTH_SHORT
            ).show()
        }

        findViewById<LinearLayout>(R.id.btnAppearance)?.setOnClickListener {
            PageTransition.slideForward(this, createReaderToolIntent(ReaderAppearanceActivity::class.java))
        }
        findViewById<LinearLayout>(R.id.btnNotes)?.setOnClickListener {
            showAllNotesBottomSheet()
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

        // Set up chapter subtitle & synopsis for fallback text preview
        if (!readerBook.subtitle.isNullOrBlank()) {
            tvChapterTitle.text = readerBook.subtitle
        } else {
            tvChapterTitle.text = readerBook.title
        }

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

        // Muat naskah PDF asli langsung di dalam aplikasi (in-app reader)
        val docUrl = readerBook.file_url ?: readerBook.download_url ?: readerBook.sample_url
        loadPdfDocument(docUrl)
    }

    private fun loadPdfDocument(rawUrl: String?) {
        val dir = File(cacheDir, "ebooks")
        if (!dir.exists()) dir.mkdirs()

        val safeId = if (readerBook.id > 0) readerBook.id else readerBook.title.hashCode()
        val localFile = File(dir, "ebook_${safeId}.pdf")

        // 1. Cek apakah berkas naskah sudah tersimpan di cache lokal
        if (localFile.exists() && localFile.length() > 2048) {
            openAndDisplayPdf(localFile)
            return
        }

        layoutPdfLoading?.visibility = View.VISIBLE
        scrollViewReader.visibility = View.GONE
        rvPdfPages?.visibility = View.GONE
        tvPdfLoadingTitle?.text = "Menyiapkan Naskah E-Book..."
        tvPdfLoadingSub?.text = "Membuka halaman dokumen di e-library..."

        Executors.newSingleThreadExecutor().execute {
            // 2. Cek apakah berkas tersedia di aset internal aplikasi (misalnya naskah resmi Asuhan Kebidanan)
            val titleLower = readerBook.title.lowercase()
            val isAsuhanKebidanan = readerBook.id == 3 || readerBook.id == 1003 ||
                    titleLower.contains("asuhan") ||
                    titleLower.contains("kebidanan") ||
                    titleLower.contains("kehamilan")

            var assetStream: InputStream? = null
            try {
                if (isAsuhanKebidanan) {
                    assetStream = assets.open("ebooks/asuhan_kebidanan.pdf")
                } else {
                    try {
                        assetStream = assets.open("ebooks/ebook_${safeId}.pdf")
                    } catch (_: Exception) {}
                }

                if (assetStream != null) {
                    copyStreamToFile(assetStream, localFile)
                    if (localFile.exists() && localFile.length() > 2048) {
                        runOnUiThread {
                            openAndDisplayPdf(localFile)
                        }
                        return@execute
                    }
                }
            } catch (e: Exception) {
                Log.e("ReaderActivity", "Error loading from assets", e)
            } finally {
                try { assetStream?.close() } catch (_: Exception) {}
            }

            // 3. Jika belum ada di cache & assets, unduh dari backend e-library
            if (!rawUrl.isNullOrBlank()) {
                val formattedUrl = RetrofitClient.getFormattedImageUrl(rawUrl) ?: rawUrl
                val tempFile = File(dir, "ebook_${safeId}_temp.pdf")
                try {
                    val request = Request.Builder().url(formattedUrl).build()
                    val response = RetrofitClient.httpClient.newCall(request).execute()

                    if (response.isSuccessful) {
                        val body = response.body
                        if (body != null) {
                            val totalBytes = body.contentLength()
                            val input = body.byteStream()
                            val output = FileOutputStream(tempFile)
                            val buffer = ByteArray(8192)
                            var bytesRead: Int
                            var downloaded = 0L

                            while (input.read(buffer).also { bytesRead = it } != -1) {
                                output.write(buffer, 0, bytesRead)
                                downloaded += bytesRead
                                if (totalBytes > 0) {
                                    val percent = ((downloaded.toFloat() / totalBytes.toFloat()) * 100).toInt()
                                    val mbDownloaded = String.format(java.util.Locale.US, "%.1f", downloaded / (1024.0 * 1024.0))
                                    val mbTotal = String.format(java.util.Locale.US, "%.1f", totalBytes / (1024.0 * 1024.0))
                                    runOnUiThread {
                                        tvPdfLoadingSub?.text = "Memuat naskah: $percent% ($mbDownloaded MB / $mbTotal MB)"
                                    }
                                }
                            }
                            output.flush()
                            output.close()
                            input.close()

                            if (tempFile.exists() && tempFile.length() > 2048) {
                                if (localFile.exists()) localFile.delete()
                                tempFile.renameTo(localFile)
                                runOnUiThread {
                                    openAndDisplayPdf(localFile)
                                }
                                return@execute
                            }
                        }
                    }
                } catch (e: Exception) {
                    Log.e("ReaderActivity", "Failed to stream PDF from server", e)
                } finally {
                    if (tempFile.exists()) tempFile.delete()
                }
            }

            // 4. Fallback jika dokumen PDF belum tersedia
            runOnUiThread {
                fallbackToTextView("Naskah e-book dibuka dalam mode pratinjau teks.")
            }
        }
    }

    private fun copyStreamToFile(input: InputStream, target: File) {
        val temp = File(target.parentFile, "${target.name}.tmp")
        FileOutputStream(temp).use { out ->
            input.copyTo(out)
            out.flush()
        }
        if (temp.exists() && temp.length() > 2048) {
            if (target.exists()) target.delete()
            temp.renameTo(target)
        } else {
            temp.delete()
        }
    }

    private fun fallbackToTextView(reason: String) {
        layoutPdfLoading?.visibility = View.GONE
        rvPdfPages?.visibility = View.GONE
        scrollViewReader.visibility = View.VISIBLE
        Toast.makeText(this, reason, Toast.LENGTH_SHORT).show()
    }

    private fun openAndDisplayPdf(file: File) {
        try {
            pdfFileDescriptor?.close()
            pdfRenderer?.close()

            val pfd = ParcelFileDescriptor.open(file, ParcelFileDescriptor.MODE_READ_ONLY)
            pdfFileDescriptor = pfd
            val renderer = PdfRenderer(pfd)
            pdfRenderer = renderer
            totalPages = renderer.pageCount

            val adapter = PdfPagesAdapter(
                context = this,
                renderer = renderer,
                book = readerBook,
                onHighlightCreated = { page, selectedText, lineStrips ->
                    showCreateHighlightNoteDialog(page, selectedText, lineStrips)
                },
                onHighlightClicked = { highlight ->
                    showViewHighlightNoteDialog(highlight)
                },
                onPageBookmarkRequested = { page ->
                    showBookmarkPageDialog(page)
                }
            )
            pdfAdapter = adapter
            val layoutManager = LinearLayoutManager(this)
            rvPdfPages?.layoutManager = layoutManager
            rvPdfPages?.adapter = adapter

            layoutPdfLoading?.visibility = View.GONE
            scrollViewReader.visibility = View.GONE
            rvPdfPages?.visibility = View.VISIBLE

            // Sinkronisasi status membaca saat menggulir halaman PDF naskah
            rvPdfPages?.clearOnScrollListeners()
            rvPdfPages?.addOnScrollListener(object : RecyclerView.OnScrollListener() {
                override fun onScrolled(recyclerView: RecyclerView, dx: Int, dy: Int) {
                    super.onScrolled(recyclerView, dx, dy)
                    val pos = layoutManager.findFirstVisibleItemPosition()
                    if (pos != RecyclerView.NO_POSITION) {
                        val activePage = pos + 1
                        currentPage = activePage
                        val progress = ((activePage.toFloat() / totalPages.toFloat()) * 100).toInt().coerceIn(0, 100)
                        progressBar.progress = progress
                        tvProgress.text = "$progress% (Hal $activePage/$totalPages)"
                        BookActivityStore.recordReadingSession(this@ReaderActivity, readerBook, activePage, totalPages, progress)
                    }
                }
            })

            // Luncurkan langsung ke halaman terakhir yang dibaca/ditandai pengguna
            val targetPos = (currentPage - 1).coerceIn(0, totalPages - 1)
            if (targetPos > 0) {
                rvPdfPages?.post {
                    layoutManager.scrollToPositionWithOffset(targetPos, 0)
                }
            }

            val initialProgress = ((currentPage.toFloat() / totalPages.toFloat()) * 100).toInt().coerceIn(0, 100)
            progressBar.progress = initialProgress
            tvProgress.text = "$initialProgress% (Hal $currentPage/$totalPages)"
        } catch (e: Exception) {
            Log.e("ReaderActivity", "Error displaying PDF", e)
            fallbackToTextView("Gagal membuka dokumen PDF: ${e.message}")
        }
    }

    private fun showBookmarkPageDialog(page: Int) {
        val dialog = BottomSheetDialog(this)
        val sheetView = layoutInflater.inflate(R.layout.layout_bookmark_page_sheet, null)
        dialog.setContentView(sheetView)

        (sheetView.parent as? View)?.setBackgroundColor(Color.TRANSPARENT)

        val tvTitle = sheetView.findViewById<TextView>(R.id.tvBookmarkSheetTitle)
        val tvSubtitle = sheetView.findViewById<TextView>(R.id.tvBookmarkSheetSubtitle)
        val btnSetBookmark = sheetView.findViewById<View>(R.id.btnSetLastReadBookmark)
        val btnCancel = sheetView.findViewById<View>(R.id.btnCancelBookmarkSheet)

        tvTitle?.text = "Tandai Halaman $page?"
        tvSubtitle?.text = if (::readerBook.isInitialized) readerBook.title else "Naskah Buku"

        btnSetBookmark?.setOnClickListener {
            BookActivityStore.setPageBookmark(this, readerBook, page)
            val calculatedProgress = ((page.toFloat() / totalPages.toFloat()) * 100).toInt().coerceIn(0, 100)
            BookActivityStore.recordReadingSession(this, readerBook, page, totalPages, calculatedProgress)
            updateBookmarkButton()
            Toast.makeText(this, "📌 Halaman $page ditandai sebagai terakhir baca. Tersimpan di Rak Buku.", Toast.LENGTH_SHORT).show()
            dialog.dismiss()
        }

        btnCancel?.setOnClickListener {
            dialog.dismiss()
        }

        dialog.setCanceledOnTouchOutside(true)
        dialog.show()
    }

    private fun showCreateHighlightNoteDialog(page: Int, selectedText: String, lineStrips: List<RectF>) {
        val dialog = BottomSheetDialog(this)
        val sheetView = layoutInflater.inflate(R.layout.layout_highlight_selection_sheet, null)
        dialog.setContentView(sheetView)

        (sheetView.parent as? View)?.setBackgroundColor(Color.TRANSPARENT)

        val tvPageBadge = sheetView.findViewById<TextView>(R.id.tvHighlightSheetPageBadge)
        tvPageBadge?.text = "Halaman $page"

        // Word-style Quote Preview
        val cardTextPreview = sheetView.findViewById<View>(R.id.cardSelectedTextPreview)
        val tvSelectedPreview = sheetView.findViewById<TextView>(R.id.tvSelectedTextPreview)
        if (selectedText.isNotBlank()) {
            cardTextPreview?.visibility = View.VISIBLE
            tvSelectedPreview?.text = "\"${selectedText.take(160)}${if (selectedText.length > 160) "..." else ""}\""
        } else {
            cardTextPreview?.visibility = View.GONE
        }

        var selectedColorHex = "#FFF176" // Default kuning

        val chipYellow = sheetView.findViewById<MaterialCardView>(R.id.chipColorYellow)
        val chipGreen = sheetView.findViewById<MaterialCardView>(R.id.chipColorGreen)
        val chipBlue = sheetView.findViewById<MaterialCardView>(R.id.chipColorBlue)
        val chipPink = sheetView.findViewById<MaterialCardView>(R.id.chipColorPink)

        val tvYellow = sheetView.findViewById<TextView>(R.id.tvLabelYellow)
        val tvGreen = sheetView.findViewById<TextView>(R.id.tvLabelGreen)
        val tvBlue = sheetView.findViewById<TextView>(R.id.tvLabelBlue)
        val tvPink = sheetView.findViewById<TextView>(R.id.tvLabelPink)

        val etNote = sheetView.findViewById<EditText>(R.id.etHighlightNoteText)
        val btnCancel = sheetView.findViewById<View>(R.id.btnCancelHighlight)
        val btnSave = sheetView.findViewById<View>(R.id.btnSaveHighlight)

        val primaryGreen = ContextCompat.getColor(this, R.color.primary_green)
        val dividerColor = ContextCompat.getColor(this, R.color.divider_color)

        fun updateColorSelection(hex: String) {
            selectedColorHex = hex

            val isY = hex == "#FFF176"
            chipYellow?.strokeWidth = if (isY) dp(2.5f) else dp(1f)
            chipYellow?.strokeColor = if (isY) primaryGreen else dividerColor
            tvYellow?.text = if (isY) "✓ Kuning" else "Kuning"

            val isG = hex == "#A5D6A7"
            chipGreen?.strokeWidth = if (isG) dp(2.5f) else dp(1f)
            chipGreen?.strokeColor = if (isG) primaryGreen else dividerColor
            tvGreen?.text = if (isG) "✓ Hijau" else "Hijau"

            val isB = hex == "#90CAF9"
            chipBlue?.strokeWidth = if (isB) dp(2.5f) else dp(1f)
            chipBlue?.strokeColor = if (isB) primaryGreen else dividerColor
            tvBlue?.text = if (isB) "✓ Biru" else "Biru"

            val isP = hex == "#F48FB1"
            chipPink?.strokeWidth = if (isP) dp(2.5f) else dp(1f)
            chipPink?.strokeColor = if (isP) primaryGreen else dividerColor
            tvPink?.text = if (isP) "✓ Pink" else "Pink"
        }

        chipYellow?.setOnClickListener { updateColorSelection("#FFF176") }
        chipGreen?.setOnClickListener { updateColorSelection("#A5D6A7") }
        chipBlue?.setOnClickListener { updateColorSelection("#90CAF9") }
        chipPink?.setOnClickListener { updateColorSelection("#F48FB1") }

        updateColorSelection(selectedColorHex)

        btnCancel?.setOnClickListener {
            dialog.dismiss()
        }

        btnSave?.setOnClickListener {
            val noteText = etNote?.text?.toString()?.trim().orEmpty()
            val bLeft = lineStrips.minOfOrNull { it.left } ?: 0f
            val bTop = lineStrips.minOfOrNull { it.top } ?: 0f
            val bRight = lineStrips.maxOfOrNull { it.right } ?: 1f
            val bBottom = lineStrips.maxOfOrNull { it.bottom } ?: 0.1f
            val lineRectsJson = SavedHighlightNote.lineRectsToJson(lineStrips)

            val newHighlight = SavedHighlightNote(
                id = UUID.randomUUID().toString(),
                bookKey = BookActivityStore.bookKey(readerBook),
                page = page,
                rectLeftRatio = bLeft,
                rectTopRatio = bTop,
                rectRightRatio = bRight,
                rectBottomRatio = bBottom,
                colorHex = selectedColorHex,
                noteText = noteText,
                selectedText = selectedText,
                lineRectsJson = lineRectsJson
            )
            BookActivityStore.saveHighlightNote(this, newHighlight)
            pdfAdapter?.refreshHighlights()
            Toast.makeText(this, "Sorotan kalimat tersimpan di Halaman $page", Toast.LENGTH_SHORT).show()
            dialog.dismiss()
        }

        dialog.show()
    }

    private fun showViewHighlightNoteDialog(highlight: SavedHighlightNote) {
        val dialog = BottomSheetDialog(this)
        val container = LinearLayout(this).apply {
            orientation = LinearLayout.VERTICAL
            setBackgroundResource(R.drawable.bg_bottom_sheet_dialog)
            setPadding(dp(22), dp(12), dp(22), dp(24))
        }

        val handle = View(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(40), dp(4)).apply {
                gravity = android.view.Gravity.CENTER_HORIZONTAL
                bottomMargin = dp(16)
            }
            setBackgroundResource(R.drawable.bg_drag_handle)
        }
        container.addView(handle)

        val titleRow = LinearLayout(this).apply {
            orientation = LinearLayout.HORIZONTAL
            gravity = android.view.Gravity.CENTER_VERTICAL
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { bottomMargin = dp(12) }
        }

        val colorDot = View(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(14), dp(14)).apply { marginEnd = dp(10) }
            background = android.graphics.drawable.GradientDrawable().apply {
                shape = android.graphics.drawable.GradientDrawable.OVAL
                val c = try { Color.parseColor(highlight.colorHex) } catch (_: Exception) { Color.parseColor("#FFF176") }
                setColor(c)
            }
        }
        titleRow.addView(colorDot)

        val tvTitle = TextView(this).apply {
            layoutParams = LinearLayout.LayoutParams(0, ViewGroup.LayoutParams.WRAP_CONTENT, 1f)
            text = "Sorotan · Halaman ${highlight.page}"
            setTextColor(ContextCompat.getColor(this@ReaderActivity, R.color.primary_green))
            textSize = 16.5f
            typeface = ResourcesCompat.getFont(this@ReaderActivity, R.font.lora)
            setTypeface(typeface, android.graphics.Typeface.BOLD)
        }
        titleRow.addView(tvTitle)
        container.addView(titleRow)

        // Tampilkan teks kalimat yang disorot jika ada
        if (highlight.selectedText.isNotBlank()) {
            val quoteCard = MaterialCardView(this).apply {
                layoutParams = LinearLayout.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.WRAP_CONTENT
                ).apply { bottomMargin = dp(12) }
                radius = dp(10).toFloat()
                cardElevation = 0f
                strokeWidth = dp(1)
                strokeColor = ContextCompat.getColor(this@ReaderActivity, R.color.outline_green)
                setCardBackgroundColor(ContextCompat.getColor(this@ReaderActivity, R.color.bg_light_green))
            }
            val tvQuote = TextView(this).apply {
                text = "❝ \"${highlight.selectedText}\""
                setTextColor(ContextCompat.getColor(this@ReaderActivity, R.color.primary_green))
                textSize = 13f
                typeface = ResourcesCompat.getFont(this@ReaderActivity, R.font.inter)
                setPadding(dp(12), dp(10), dp(12), dp(10))
                setLineSpacing(0f, 1.15f)
            }
            quoteCard.addView(tvQuote)
            container.addView(quoteCard)
        }

        val card = MaterialCardView(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { bottomMargin = dp(20) }
            radius = dp(12).toFloat()
            cardElevation = 0f
            strokeWidth = dp(1)
            strokeColor = ContextCompat.getColor(this@ReaderActivity, R.color.divider_color)
            setCardBackgroundColor(Color.parseColor("#F9F8F4"))
        }

        val tvContent = TextView(this).apply {
            text = if (highlight.noteText.isNotBlank()) highlight.noteText else "Bagian kalimat disorot (${highlight.getLineRects().size} baris) tanpa catatan teks."
            setTextColor(Color.parseColor(if (highlight.noteText.isNotBlank()) "#2D3748" else "#888888"))
            textSize = 13.5f
            typeface = ResourcesCompat.getFont(this@ReaderActivity, R.font.inter)
            setPadding(dp(14), dp(12), dp(14), dp(12))
            if (highlight.noteText.isBlank()) {
                setTypeface(typeface, android.graphics.Typeface.ITALIC)
            }
        }
        card.addView(tvContent)
        container.addView(card)

        val btnRow = LinearLayout(this).apply {
            orientation = LinearLayout.HORIZONTAL
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            )
        }

        val btnDelete = MaterialButton(this).apply {
            layoutParams = LinearLayout.LayoutParams(0, dp(46), 1f).apply { marginEnd = dp(6) }
            text = "Hapus Sorotan"
            isAllCaps = false
            textSize = 13f
            setTextColor(Color.parseColor("#D32F2F"))
            strokeWidth = dp(1)
            strokeColor = ColorStateList.valueOf(Color.parseColor("#E0E0E0"))
            cornerRadius = dp(10)
            setBackgroundColor(Color.WHITE)
            setOnClickListener {
                BookActivityStore.deleteHighlightNote(this@ReaderActivity, highlight.id)
                pdfAdapter?.refreshHighlights()
                Toast.makeText(this@ReaderActivity, "Sorotan telah dihapus.", Toast.LENGTH_SHORT).show()
                dialog.dismiss()
            }
        }
        btnRow.addView(btnDelete)

        val btnClose = MaterialButton(this).apply {
            layoutParams = LinearLayout.LayoutParams(0, dp(46), 1.2f).apply { marginStart = dp(6) }
            text = "Tutup"
            isAllCaps = false
            textSize = 13f
            setTextColor(Color.WHITE)
            cornerRadius = dp(10)
            backgroundTintList = ColorStateList.valueOf(ContextCompat.getColor(this@ReaderActivity, R.color.primary_green))
            setOnClickListener {
                dialog.dismiss()
            }
        }
        btnRow.addView(btnClose)
        container.addView(btnRow)

        dialog.setContentView(container)
        (container.parent as? View)?.setBackgroundColor(Color.TRANSPARENT)
        dialog.show()
    }

    private fun showAllNotesBottomSheet() {
        val notes = BookActivityStore.getHighlightsForBook(this, readerBook)
        if (notes.isEmpty()) {
            val dialog = BottomSheetDialog(this)
            val sheetView = layoutInflater.inflate(R.layout.layout_empty_notes_sheet, null)
            dialog.setContentView(sheetView)
            (sheetView.parent as? View)?.setBackgroundColor(Color.TRANSPARENT)

            val btnDismiss = sheetView.findViewById<View>(R.id.btnDismissEmptyNotes)
            btnDismiss?.setOnClickListener {
                dialog.dismiss()
            }
            dialog.show()
            return
        }

        val dialog = BottomSheetDialog(this)
        val container = LinearLayout(this).apply {
            orientation = LinearLayout.VERTICAL
            setBackgroundResource(R.drawable.bg_bottom_sheet_dialog)
            setPadding(dp(22), dp(12), dp(22), dp(24))
        }

        val handle = View(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(40), dp(4)).apply {
                gravity = android.view.Gravity.CENTER_HORIZONTAL
                bottomMargin = dp(16)
            }
            setBackgroundResource(R.drawable.bg_drag_handle)
        }
        container.addView(handle)

        val tvHeader = TextView(this).apply {
            text = "Daftar Catatan & Sorotan (${notes.size})"
            setTextColor(ContextCompat.getColor(this@ReaderActivity, R.color.primary_green))
            textSize = 17f
            typeface = ResourcesCompat.getFont(this@ReaderActivity, R.font.lora)
            setTypeface(typeface, android.graphics.Typeface.BOLD)
            setPadding(0, 0, 0, dp(12))
        }
        container.addView(tvHeader)

        val scrollView = NestedScrollView(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                dp(340).coerceAtMost((resources.displayMetrics.heightPixels * 0.5f).toInt())
            )
        }
        val itemsLayout = LinearLayout(this).apply {
            orientation = LinearLayout.VERTICAL
        }

        notes.forEach { note ->
            val noteCard = MaterialCardView(this).apply {
                layoutParams = LinearLayout.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.WRAP_CONTENT
                ).apply { bottomMargin = dp(10) }
                radius = dp(12).toFloat()
                cardElevation = 0f
                strokeWidth = dp(1)
                strokeColor = ContextCompat.getColor(this@ReaderActivity, R.color.divider_color)
                setCardBackgroundColor(Color.parseColor("#F9F8F4"))
            }

            val cardContent = LinearLayout(this).apply {
                orientation = LinearLayout.VERTICAL
                setPadding(dp(14), dp(12), dp(14), dp(12))
            }

            val topRow = LinearLayout(this).apply {
                orientation = LinearLayout.HORIZONTAL
                gravity = android.view.Gravity.CENTER_VERTICAL
                layoutParams = LinearLayout.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.WRAP_CONTENT
                ).apply { bottomMargin = dp(6) }
            }

            val colorDot = View(this).apply {
                layoutParams = LinearLayout.LayoutParams(dp(12), dp(12)).apply { marginEnd = dp(8) }
                background = android.graphics.drawable.GradientDrawable().apply {
                    shape = android.graphics.drawable.GradientDrawable.OVAL
                    val c = try { Color.parseColor(note.colorHex) } catch (_: Exception) { Color.parseColor("#FFF176") }
                    setColor(c)
                }
            }
            topRow.addView(colorDot)

            val tvPage = TextView(this).apply {
                layoutParams = LinearLayout.LayoutParams(0, ViewGroup.LayoutParams.WRAP_CONTENT, 1f)
                text = "Halaman ${note.page}"
                setTextColor(ContextCompat.getColor(this@ReaderActivity, R.color.primary_green))
                textSize = 13f
                typeface = ResourcesCompat.getFont(this@ReaderActivity, R.font.inter)
                setTypeface(typeface, android.graphics.Typeface.BOLD)
            }
            topRow.addView(tvPage)

            val btnDelete = ImageView(this).apply {
                layoutParams = LinearLayout.LayoutParams(dp(28), dp(28))
                setImageResource(android.R.drawable.ic_menu_delete)
                setColorFilter(Color.parseColor("#999999"))
                setPadding(dp(4), dp(4), dp(4), dp(4))
                setOnClickListener {
                    BookActivityStore.deleteHighlightNote(this@ReaderActivity, note.id)
                    pdfAdapter?.refreshHighlights()
                    dialog.dismiss()
                    Toast.makeText(this@ReaderActivity, "Catatan/sorotan telah dihapus", Toast.LENGTH_SHORT).show()
                    showAllNotesBottomSheet()
                }
            }
            topRow.addView(btnDelete)
            cardContent.addView(topRow)

            if (note.selectedText.isNotBlank()) {
                val tvQuoteSnippet = TextView(this).apply {
                    text = "❝ \"${note.selectedText.take(65)}${if (note.selectedText.length > 65) "..." else ""}\""
                    setTextColor(ContextCompat.getColor(this@ReaderActivity, R.color.primary_green))
                    textSize = 12f
                    typeface = ResourcesCompat.getFont(this@ReaderActivity, R.font.inter)
                    setTypeface(typeface, android.graphics.Typeface.BOLD)
                    setPadding(0, 0, 0, dp(4))
                }
                cardContent.addView(tvQuoteSnippet)
            }

            val tvSnippet = TextView(this).apply {
                text = if (note.noteText.isNotBlank()) "Catatan: ${note.noteText}" else "(Kalimat teks disorot ${note.getLineRects().size} baris)"
                setTextColor(Color.parseColor(if (note.noteText.isNotBlank()) "#2D3748" else "#888888"))
                textSize = 12.5f
                typeface = ResourcesCompat.getFont(this@ReaderActivity, R.font.inter)
                if (note.noteText.isBlank()) {
                    setTypeface(typeface, android.graphics.Typeface.ITALIC)
                }
            }
            cardContent.addView(tvSnippet)

            noteCard.addView(cardContent)
            noteCard.setOnClickListener {
                dialog.dismiss()
                val targetPos = (note.page - 1).coerceIn(0, totalPages - 1)
                rvPdfPages?.post {
                    (rvPdfPages?.layoutManager as? LinearLayoutManager)?.scrollToPositionWithOffset(targetPos, 0)
                }
                Toast.makeText(this, "Melompat ke Halaman ${note.page}", Toast.LENGTH_SHORT).show()
            }

            itemsLayout.addView(noteCard)
        }

        scrollView.addView(itemsLayout)
        container.addView(scrollView)

        dialog.setContentView(container)
        (container.parent as? View)?.setBackgroundColor(Color.TRANSPARENT)
        dialog.show()
    }

    private fun applyAppearanceSettings() {
        val prefs = getSharedPreferences(ReaderAppearanceActivity.PREFS_READER, Context.MODE_PRIVATE)
        val fontSize = prefs.getInt(ReaderAppearanceActivity.KEY_FONT_SIZE, 16)
        val theme = prefs.getString(ReaderAppearanceActivity.KEY_READER_THEME, "GELAP") ?: "GELAP"

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
        val dividerColor: Int
        val progressTint: Int

        when (theme) {
            "TERANG" -> {
                backgroundColor = ContextCompat.getColor(this, R.color.bg_cream)
                headingColor = ContextCompat.getColor(this, R.color.primary_green)
                textColor = ContextCompat.getColor(this, R.color.text_dark)
                mutedColor = ContextCompat.getColor(this, R.color.text_muted)
                dividerColor = ContextCompat.getColor(this, R.color.divider_color)
                progressTint = ContextCompat.getColor(this, R.color.primary_green)
            }
            "GELAP" -> {
                backgroundColor = Color.parseColor("#181E1B")
                headingColor = Color.parseColor("#A7D7C5")
                textColor = Color.parseColor("#D1D5DB")
                mutedColor = Color.parseColor("#8E9F97")
                dividerColor = Color.parseColor("#2E3A34")
                progressTint = Color.parseColor("#4CAF50")
            }
            else -> { // "SEPIA"
                backgroundColor = Color.parseColor("#F4E8D3")
                headingColor = Color.parseColor("#203C32")
                textColor = Color.parseColor("#433422")
                mutedColor = Color.parseColor("#7A6A55")
                dividerColor = Color.parseColor("#DDD0B8")
                progressTint = Color.parseColor("#203C32")
            }
        }

        layoutReaderRoot.setBackgroundColor(backgroundColor)
        toolbarReader.setBackgroundColor(backgroundColor)
        layoutReaderBottomControls.setBackgroundColor(backgroundColor)
        rvPdfPages?.setBackgroundColor(backgroundColor)

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
    } catch (_: ClassCastException) {
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

    override fun onDestroy() {
        super.onDestroy()
        pdfAdapter?.clear()
        try {
            pdfRenderer?.close()
        } catch (_: Exception) {}
        try {
            pdfFileDescriptor?.close()
        } catch (_: Exception) {}
    }

    private fun showTableOfContentsDialog() {
        if (pdfRenderer != null && totalPages > 1) {
            val step = maxOf(1, totalPages / 15)
            val pageItems = mutableListOf<String>()
            val pageTargets = mutableListOf<Int>()

            for (p in 1..totalPages step step) {
                pageItems.add("Halaman $p")
                pageTargets.add(p)
            }
            if (!pageTargets.contains(totalPages)) {
                pageItems.add("Halaman $totalPages (Selesai)")
                pageTargets.add(totalPages)
            }

            AlertDialog.Builder(this)
                .setTitle("${readerBook.title} · Lompat Halaman")
                .setItems(pageItems.toTypedArray()) { _, index ->
                    val target = pageTargets[index]
                    rvPdfPages?.scrollToPosition(target - 1)
                    Toast.makeText(this, "Membuka Halaman $target", Toast.LENGTH_SHORT).show()
                }
                .setNegativeButton("Tutup", null)
                .show()
        } else {
            val chapters = arrayOf(
                "BAB 1 · Pengantar & Landasan Teori",
                "BAB 2 · Metodologi dan Pendekatan Kajian",
                "BAB 3 · Analisis Temuan dan Diskusi Akademik",
                "BAB 4 · Implementasi Praktis & Best Practice",
                "BAB 5 · Kesimpulan, Refleksi, dan Rekomendasi"
            )
            AlertDialog.Builder(this)
                .setTitle("${readerBook.title} · Daftar Isi")
                .setItems(chapters) { _, index ->
                    tvChapterNum.text = "BAB ${index + 1}"
                    val titlePart = chapters[index].substringAfter(" · ")
                    tvChapterTitle.text = titlePart
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
    }

    private fun dp(value: Int): Int = (value * resources.displayMetrics.density).toInt()
    private fun dp(value: Float): Int = (value * resources.displayMetrics.density).toInt()

    companion object {
        const val EXTRA_BOOK = "BOOK_OBJECT"
        const val EXTRA_PAGE = "BOOKMARK_PAGE"
    }
}
