package com.example.penacendekia

import android.content.Context
import android.graphics.Canvas
import android.graphics.Color
import android.graphics.Matrix
import android.graphics.Paint
import android.graphics.PointF
import android.graphics.RectF
import android.graphics.drawable.Drawable
import android.util.AttributeSet
import android.view.GestureDetector
import android.view.HapticFeedbackConstants
import android.view.MotionEvent
import android.view.ScaleGestureDetector
import androidx.appcompat.widget.AppCompatImageView
import kotlin.math.abs
import kotlin.math.max
import kotlin.math.min
import kotlin.math.sqrt

/**
 * ZoomableImageView untuk Halaman PDF:
 * - Menyesuaikan lebar pas layar secara default (fit-to-width).
 * - Mendukung pinch-to-zoom & double tap zoom.
 * - Mendukung TEKAN-TAHAN di halaman (Long-Press tanpa geser) untuk memunculkan
 *   opsi "Tandai Sebagai Terakhir Baca" (Sinkron dengan Lanjut Membaca di Rak Buku).
 * - Mendukung TEKAN-TAHAN-GESER pada kalimat untuk memilih teks dan memberi warna sorotan
 *   (Kuning, Hijau, Biru, Pink) serta membuat catatan tanpa opsi salin/tempel (DRM Protected).
 */
class ZoomableImageView @JvmOverloads constructor(
    context: Context,
    attrs: AttributeSet? = null,
    defStyleAttr: Int = 0
) : AppCompatImageView(context, attrs, defStyleAttr) {

    private val currentMatrix = Matrix()
    private val matrixValues = FloatArray(9)

    private val minScale = 1.0f
    private val maxScale = 4.0f

    private val scaleDetector: ScaleGestureDetector
    private val gestureDetector: GestureDetector

    // Callback Events
    var pageIndex: Int = 0
    var onHighlightCreated: ((page: Int, selectedText: String, lineStrips: List<RectF>) -> Unit)? = null
    var onHighlightClicked: ((highlight: SavedHighlightNote) -> Unit)? = null
    var onPageBookmarkRequested: ((page: Int) -> Unit)? = null

    // Word-Style Text Recognition Data & Active Selection
    var pageTextData: PageTextData? = null
    private val activeSelectionStrips = mutableListOf<RectF>()
    private var activeSelectionText = ""

    // Touch & Selection State
    private var isLongPressed = false
    private var isDraggingHighlight = false
    private val touchStart = PointF()
    private val touchCurrent = PointF()
    private val touchSlop = 12f * resources.displayMetrics.density

    private val pageHighlights = mutableListOf<SavedHighlightNote>()

    private val highlightPaint = Paint(Paint.ANTI_ALIAS_FLAG).apply {
        style = Paint.Style.FILL
    }
    private val activeSelectionPaint = Paint(Paint.ANTI_ALIAS_FLAG).apply {
        style = Paint.Style.FILL
        color = Color.parseColor("#452E7D32") // Translucent green selection like Word/e-reader
    }
    private val selectionHandlePaint = Paint(Paint.ANTI_ALIAS_FLAG).apply {
        style = Paint.Style.FILL
        color = Color.parseColor("#1B3B2B")
    }
    private val noteBadgePaint = Paint(Paint.ANTI_ALIAS_FLAG).apply {
        style = Paint.Style.FILL
        color = Color.parseColor("#1B3B2B")
    }
    private val noteBadgeTextPaint = Paint(Paint.ANTI_ALIAS_FLAG).apply {
        color = Color.WHITE
        textSize = 9f * resources.displayMetrics.scaledDensity
        textAlign = Paint.Align.CENTER
        isFakeBoldText = true
    }

    init {
        scaleType = ScaleType.MATRIX
        imageMatrix = currentMatrix

        scaleDetector = ScaleGestureDetector(context, object : ScaleGestureDetector.SimpleOnScaleGestureListener() {
            override fun onScale(detector: ScaleGestureDetector): Boolean {
                if (isLongPressed || isDraggingHighlight) return false
                val scaleFactor = detector.scaleFactor
                val current = getCurrentScale()
                val target = (current * scaleFactor).coerceIn(minScale, maxScale)
                val factor = target / current

                currentMatrix.postScale(factor, factor, detector.focusX, detector.focusY)
                checkBoundsAndCenter()
                imageMatrix = currentMatrix
                parent?.requestDisallowInterceptTouchEvent(true)
                return true
            }

            override fun onScaleBegin(detector: ScaleGestureDetector): Boolean {
                if (isLongPressed || isDraggingHighlight) return false
                parent?.requestDisallowInterceptTouchEvent(true)
                return true
            }
        })

        gestureDetector = GestureDetector(context, object : GestureDetector.SimpleOnGestureListener() {
            override fun onSingleTapConfirmed(e: MotionEvent): Boolean {
                val clicked = findHighlightAtPoint(e.x, e.y)
                if (clicked != null) {
                    onHighlightClicked?.invoke(clicked)
                    return true
                }
                return false
            }

            override fun onDoubleTap(e: MotionEvent): Boolean {
                if (isLongPressed || isDraggingHighlight) return false
                val current = getCurrentScale()
                val target = if (current > 1.3f) 1.0f else 2.5f
                val factor = target / current

                currentMatrix.postScale(factor, factor, e.x, e.y)
                checkBoundsAndCenter()
                imageMatrix = currentMatrix
                parent?.requestDisallowInterceptTouchEvent(getCurrentScale() > 1.05f)
                return true
            }

            override fun onLongPress(e: MotionEvent) {
                // Pengguna menekan dan menahan jari di layar
                isLongPressed = true
                isDraggingHighlight = false
                touchStart.set(e.x, e.y)
                touchCurrent.set(e.x, e.y)
                performHapticFeedback(HapticFeedbackConstants.LONG_PRESS)
                parent?.requestDisallowInterceptTouchEvent(true)
                invalidate()
            }

            override fun onScroll(e1: MotionEvent?, e2: MotionEvent, distanceX: Float, distanceY: Float): Boolean {
                if (isLongPressed || isDraggingHighlight) return false
                val current = getCurrentScale()
                if (current > 1.05f) {
                    parent?.requestDisallowInterceptTouchEvent(true)
                    currentMatrix.postTranslate(-distanceX, -distanceY)
                    checkBoundsAndCenter()
                    imageMatrix = currentMatrix
                    return true
                }
                parent?.requestDisallowInterceptTouchEvent(false)
                return false
            }
        })
    }

    fun setHighlights(highlights: List<SavedHighlightNote>) {
        pageHighlights.clear()
        pageHighlights.addAll(highlights)
        invalidate()
    }

    override fun onMeasure(widthMeasureSpec: Int, heightMeasureSpec: Int) {
        val d = drawable
        if (d != null && d.intrinsicWidth > 0 && d.intrinsicHeight > 0) {
            val widthSize = MeasureSpec.getSize(widthMeasureSpec)
            val calculatedHeight = (widthSize.toFloat() * (d.intrinsicHeight.toFloat() / d.intrinsicWidth.toFloat())).toInt()
            setMeasuredDimension(widthSize, calculatedHeight)
        } else {
            super.onMeasure(widthMeasureSpec, heightMeasureSpec)
        }
    }

    override fun setImageDrawable(drawable: Drawable?) {
        super.setImageDrawable(drawable)
        post { resetToFit() }
    }

    override fun onSizeChanged(w: Int, h: Int, oldw: Int, oldh: Int) {
        super.onSizeChanged(w, h, oldw, oldh)
        resetToFit()
    }

    fun resetToFit() {
        val d = drawable ?: return
        val viewW = width.toFloat()
        val viewH = height.toFloat()
        if (viewW <= 0f || viewH <= 0f) return

        val dW = d.intrinsicWidth.toFloat()
        val dH = d.intrinsicHeight.toFloat()
        if (dW <= 0f || dH <= 0f) return

        currentMatrix.reset()
        val scale = viewW / dW
        currentMatrix.postScale(scale, scale)

        val scaledH = dH * scale
        if (scaledH < viewH) {
            val dy = (viewH - scaledH) / 2f
            currentMatrix.postTranslate(0f, dy)
        }
        checkBoundsAndCenter()
        imageMatrix = currentMatrix
    }

    private fun checkBoundsAndCenter() {
        val d = drawable ?: return
        val dW = d.intrinsicWidth.toFloat()
        val dH = d.intrinsicHeight.toFloat()
        if (dW <= 0f || dH <= 0f) return

        val rect = RectF(0f, 0f, dW, dH)
        currentMatrix.mapRect(rect)

        val viewW = width.toFloat()
        val viewH = height.toFloat()
        if (viewW <= 0f || viewH <= 0f) return

        var deltaX = 0f
        var deltaY = 0f

        if (rect.width() <= viewW) {
            deltaX = (viewW - rect.width()) / 2f - rect.left
        } else {
            if (rect.left > 0f) deltaX = -rect.left
            else if (rect.right < viewW) deltaX = viewW - rect.right
        }

        if (rect.height() <= viewH) {
            deltaY = (viewH - rect.height()) / 2f - rect.top
        } else {
            if (rect.top > 0f) deltaY = -rect.top
            else if (rect.bottom < viewH) deltaY = viewH - rect.bottom
        }

        currentMatrix.postTranslate(deltaX, deltaY)
    }

    fun getCurrentScale(): Float {
        currentMatrix.getValues(matrixValues)
        val sx = matrixValues[Matrix.MSCALE_X]
        val sy = matrixValues[Matrix.MSKEW_Y]
        return sqrt((sx * sx + sy * sy).toDouble()).toFloat()
    }

    override fun onTouchEvent(event: MotionEvent): Boolean {
        scaleDetector.onTouchEvent(event)
        gestureDetector.onTouchEvent(event)

        if (isLongPressed) {
            when (event.actionMasked) {
                MotionEvent.ACTION_MOVE -> {
                    val dx = abs(event.x - touchStart.x)
                    val dy = abs(event.y - touchStart.y)
                    if (dx > touchSlop || dy > (touchSlop / 1.5f)) {
                        isDraggingHighlight = true
                        touchCurrent.set(event.x, event.y)
                        parent?.requestDisallowInterceptTouchEvent(true)

                        val startNorm = screenPointToNormalized(touchStart.x, touchStart.y)
                        val curNorm = screenPointToNormalized(event.x, event.y)

                        if (startNorm != null && curNorm != null) {
                            val data = pageTextData ?: PdfPageTextManager.getCachedText(pageIndex)
                            if (data != null && data.words.isNotEmpty()) {
                                val wStart = PdfPageTextManager.findNearestWord(startNorm.x, startNorm.y, data.words)
                                val wCur = PdfPageTextManager.findNearestWord(curNorm.x, curNorm.y, data.words)
                                if (wStart != null && wCur != null) {
                                    val (txt, strips) = PdfPageTextManager.computeSelectionStrips(wStart, wCur, data.lines, data.words)
                                    activeSelectionText = txt
                                    activeSelectionStrips.clear()
                                    activeSelectionStrips.addAll(strips)
                                }
                            } else {
                                val strips = PdfPageTextManager.computeFallbackStrips(startNorm, curNorm)
                                activeSelectionText = ""
                                activeSelectionStrips.clear()
                                activeSelectionStrips.addAll(strips)
                            }
                        }
                        invalidate()
                    }
                    return true
                }
                MotionEvent.ACTION_UP -> {
                    isLongPressed = false
                    parent?.requestDisallowInterceptTouchEvent(false)
                    if (isDraggingHighlight) {
                        // User melakukan TEKAN-TAHAN-GESER pada kalimat -> Buat Sorotan Kalimat Word-Style
                        isDraggingHighlight = false
                        invalidate()
                        if (activeSelectionStrips.isNotEmpty()) {
                            val stripsCopy = ArrayList(activeSelectionStrips)
                            val textCopy = activeSelectionText
                            activeSelectionStrips.clear()
                            activeSelectionText = ""
                            onHighlightCreated?.invoke(pageIndex, textCopy, stripsCopy)
                        }
                    } else {
                        // User melakukan TEKAN-TAHAN BIASA di halaman tanpa geser -> Munculkan opsi Tandai Sebagai Terakhir Baca
                        activeSelectionStrips.clear()
                        activeSelectionText = ""
                        invalidate()
                        onPageBookmarkRequested?.invoke(pageIndex)
                    }
                    return true
                }
                MotionEvent.ACTION_CANCEL -> {
                    isLongPressed = false
                    isDraggingHighlight = false
                    activeSelectionStrips.clear()
                    activeSelectionText = ""
                    invalidate()
                    parent?.requestDisallowInterceptTouchEvent(false)
                    return true
                }
            }
        }

        when (event.actionMasked) {
            MotionEvent.ACTION_DOWN -> {
                if (getCurrentScale() > 1.05f) {
                    parent?.requestDisallowInterceptTouchEvent(true)
                }
            }
            MotionEvent.ACTION_POINTER_DOWN -> {
                parent?.requestDisallowInterceptTouchEvent(true)
            }
            MotionEvent.ACTION_UP, MotionEvent.ACTION_CANCEL -> {
                if (getCurrentScale() <= 1.05f) {
                    resetToFit()
                    parent?.requestDisallowInterceptTouchEvent(false)
                }
            }
        }
        return true
    }

    fun screenPointToNormalized(screenX: Float, screenY: Float): PointF? {
        val d = drawable ?: return null
        val dW = d.intrinsicWidth.toFloat()
        val dH = d.intrinsicHeight.toFloat()
        if (dW <= 0f || dH <= 0f) return null

        val inverse = Matrix()
        if (!currentMatrix.invert(inverse)) return null

        val pts = floatArrayOf(screenX, screenY)
        inverse.mapPoints(pts)

        val normX = (pts[0] / dW).coerceIn(0f, 1f)
        val normY = (pts[1] / dH).coerceIn(0f, 1f)
        return PointF(normX, normY)
    }

    private fun findHighlightAtPoint(x: Float, y: Float): SavedHighlightNote? {
        val d = drawable ?: return null
        val dW = d.intrinsicWidth.toFloat()
        val dH = d.intrinsicHeight.toFloat()
        if (dW <= 0f || dH <= 0f) return null

        val rect = RectF()
        val tolerance = 4f * resources.displayMetrics.density

        for (h in pageHighlights.reversed()) {
            for (strip in h.getLineRects()) {
                rect.set(
                    strip.left * dW,
                    strip.top * dH,
                    strip.right * dW,
                    strip.bottom * dH
                )
                currentMatrix.mapRect(rect)
                val hitRect = RectF(rect.left - tolerance, rect.top - tolerance, rect.right + tolerance, rect.bottom + tolerance)
                if (hitRect.contains(x, y)) {
                    return h
                }
            }
        }
        return null
    }

    override fun onDraw(canvas: Canvas) {
        super.onDraw(canvas)

        val d = drawable ?: return
        val dW = d.intrinsicWidth.toFloat()
        val dH = d.intrinsicHeight.toFloat()
        if (dW <= 0f || dH <= 0f) return

        val corner = 3.5f * resources.displayMetrics.density
        val mappedRect = RectF()

        // 1. Gambar semua sorotan yang tersimpan di halaman ini (Word-style line strips)
        for (h in pageHighlights) {
            val baseColor = try {
                Color.parseColor(h.colorHex)
            } catch (_: Exception) {
                Color.parseColor("#FFF176")
            }
            highlightPaint.color = baseColor
            highlightPaint.alpha = 135 // Transparan ~50% agar teks di bawahnya tetap terlihat tajam

            val lineRects = h.getLineRects()
            var lastScreenRect: RectF? = null

            for (strip in lineRects) {
                mappedRect.set(
                    strip.left * dW,
                    strip.top * dH,
                    strip.right * dW,
                    strip.bottom * dH
                )
                currentMatrix.mapRect(mappedRect)
                canvas.drawRoundRect(mappedRect, corner, corner, highlightPaint)
                lastScreenRect = RectF(mappedRect)
            }

            // Lencana pensil ✎ jika memiliki catatan teks di akhir strip terakhir
            if (h.noteText.isNotBlank() && lastScreenRect != null) {
                val badgeRadius = 6.5f * resources.displayMetrics.density
                val badgeCx = lastScreenRect.right + (3f * resources.displayMetrics.density)
                val badgeCy = lastScreenRect.top + (lastScreenRect.height() / 2f)
                canvas.drawCircle(badgeCx, badgeCy, badgeRadius, noteBadgePaint)
                canvas.drawText("✎", badgeCx, badgeCy + (2.5f * resources.displayMetrics.density), noteBadgeTextPaint)
            }
        }

        // 2. Gambar area seleksi aktif Word-style saat pengguna sedang melakukan TEKAN-TAHAN-GESER pada kalimat
        if (isDraggingHighlight && activeSelectionStrips.isNotEmpty()) {
            val stripScreen = RectF()
            var firstScreen: RectF? = null
            var lastScreen: RectF? = null

            for (strip in activeSelectionStrips) {
                stripScreen.set(
                    strip.left * dW,
                    strip.top * dH,
                    strip.right * dW,
                    strip.bottom * dH
                )
                currentMatrix.mapRect(stripScreen)
                canvas.drawRoundRect(stripScreen, corner, corner, activeSelectionPaint)

                if (firstScreen == null) {
                    firstScreen = RectF(stripScreen)
                }
                lastScreen = RectF(stripScreen)
            }

            // Word-style selection handles di awal strip pertama dan akhir strip terakhir
            if (firstScreen != null && lastScreen != null) {
                val handleRadius = 4.5f * resources.displayMetrics.density
                canvas.drawCircle(firstScreen.left, firstScreen.top, handleRadius, selectionHandlePaint)
                canvas.drawCircle(lastScreen.right, lastScreen.bottom, handleRadius, selectionHandlePaint)
            }
        }
    }
}
