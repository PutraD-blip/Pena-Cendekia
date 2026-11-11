package com.example.penacendekia

import android.graphics.Bitmap
import android.graphics.PointF
import android.graphics.RectF
import android.util.Log
import android.util.LruCache
import com.google.mlkit.vision.common.InputImage
import com.google.mlkit.vision.text.TextRecognition
import com.google.mlkit.vision.text.latin.TextRecognizerOptions
import kotlin.math.abs
import kotlin.math.max
import kotlin.math.min
import kotlin.math.sqrt

data class PageWord(
    val text: String,
    val bounds: RectF, // Normalized 0.0..1.0
    val lineIndex: Int,
    val wordIndex: Int
)

data class PageTextLine(
    val lineIndex: Int,
    val bounds: RectF, // Normalized 0.0..1.0
    val words: List<PageWord>
)

data class PageTextData(
    val pageIndex: Int,
    val lines: List<PageTextLine>,
    val words: List<PageWord>
)

object PdfPageTextManager {
    private const val TAG = "PdfPageTextManager"
    private val recognizer = TextRecognition.getClient(TextRecognizerOptions.DEFAULT_OPTIONS)
    private val pageCache = LruCache<Int, PageTextData>(25)

    fun getCachedText(pageIndex: Int): PageTextData? = pageCache.get(pageIndex)

    fun extractText(pageIndex: Int, bitmap: Bitmap, onComplete: (PageTextData) -> Unit) {
        val cached = pageCache.get(pageIndex)
        if (cached != null) {
            onComplete(cached)
            return
        }

        try {
            val image = InputImage.fromBitmap(bitmap, 0)
            recognizer.process(image)
                .addOnSuccessListener { visionText ->
                    val width = bitmap.width.toFloat()
                    val height = bitmap.height.toFloat()
                    val linesList = mutableListOf<PageTextLine>()
                    val allWords = mutableListOf<PageWord>()
                    var lineCounter = 0

                    for (block in visionText.textBlocks) {
                        for (line in block.lines) {
                            val lineBox = line.boundingBox
                            val normLineBox = if (lineBox != null && width > 0f && height > 0f) {
                                RectF(
                                    (lineBox.left / width).coerceIn(0f, 1f),
                                    (lineBox.top / height).coerceIn(0f, 1f),
                                    (lineBox.right / width).coerceIn(0f, 1f),
                                    (lineBox.bottom / height).coerceIn(0f, 1f)
                                )
                            } else {
                                continue
                            }

                            val lineWords = mutableListOf<PageWord>()
                            for ((wIdx, element) in line.elements.withIndex()) {
                                val elemBox = element.boundingBox ?: continue
                                val normElemBox = RectF(
                                    (elemBox.left / width).coerceIn(0f, 1f),
                                    (elemBox.top / height).coerceIn(0f, 1f),
                                    (elemBox.right / width).coerceIn(0f, 1f),
                                    (elemBox.bottom / height).coerceIn(0f, 1f)
                                )
                                val pw = PageWord(element.text, normElemBox, lineCounter, wIdx)
                                lineWords.add(pw)
                                allWords.add(pw)
                            }

                            if (lineWords.isNotEmpty()) {
                                linesList.add(PageTextLine(lineCounter, normLineBox, lineWords))
                                lineCounter++
                            }
                        }
                    }

                    val result = PageTextData(pageIndex, linesList, allWords)
                    pageCache.put(pageIndex, result)
                    onComplete(result)
                }
                .addOnFailureListener { e ->
                    Log.w(TAG, "OCR recognition failed for page $pageIndex: ${e.message}")
                    val fallback = PageTextData(pageIndex, emptyList(), emptyList())
                    onComplete(fallback)
                }
        } catch (e: Exception) {
            Log.e(TAG, "Error in OCR processing", e)
            val fallback = PageTextData(pageIndex, emptyList(), emptyList())
            onComplete(fallback)
        }
    }

    /**
     * Cari kata terdekat dari koordinat ternormalisasi (0.0..1.0)
     */
    fun findNearestWord(normX: Float, normY: Float, words: List<PageWord>): PageWord? {
        if (words.isEmpty()) return null

        // 1. Cek apakah ada kata yang tepat memuat koordinat (dengan sedikit toleransi)
        val tolerance = 0.015f
        for (w in words) {
            val expanded = RectF(
                w.bounds.left - tolerance,
                w.bounds.top - tolerance,
                w.bounds.right + tolerance,
                w.bounds.bottom + tolerance
            )
            if (expanded.contains(normX, normY)) {
                return w
            }
        }

        // 2. Cari kata dengan jarak Euclidean terpendek
        var closest: PageWord? = null
        var minDistance = Float.MAX_VALUE

        for (w in words) {
            val cx = (w.bounds.left + w.bounds.right) / 2f
            val cy = (w.bounds.top + w.bounds.bottom) / 2f
            val dx = normX - cx
            // Beri bobot vertikal lebih besar agar memilih baris yang sesuai
            val dy = (normY - cy) * 2.5f
            val dist = sqrt((dx * dx + dy * dy).toDouble()).toFloat()
            if (dist < minDistance) {
                minDistance = dist
                closest = w
            }
        }
        return closest
    }

    /**
     * Hitung strip sorotan teks per baris (Word-style) antara dua kata terpilih
     */
    fun computeSelectionStrips(
        startWord: PageWord,
        endWord: PageWord,
        lines: List<PageTextLine>,
        words: List<PageWord>
    ): Pair<String, List<RectF>> {
        val startOrder = startWord.lineIndex * 10000 + startWord.wordIndex
        val endOrder = endWord.lineIndex * 10000 + endWord.wordIndex
        val (first, last) = if (startOrder <= endOrder) startWord to endWord else endWord to startWord

        val firstOrder = first.lineIndex * 10000 + first.wordIndex
        val lastOrder = last.lineIndex * 10000 + last.wordIndex

        val selectedWords = words.filter { w ->
            val order = w.lineIndex * 10000 + w.wordIndex
            order in firstOrder..lastOrder
        }

        val selectedText = selectedWords.joinToString(" ") { it.text }

        val strips = mutableListOf<RectF>()
        for (lineIdx in first.lineIndex..last.lineIndex) {
            val wordsOnLine = selectedWords.filter { it.lineIndex == lineIdx }
            if (wordsOnLine.isEmpty()) continue

            val line = lines.find { it.lineIndex == lineIdx }
            val lineTop = line?.bounds?.top ?: wordsOnLine.first().bounds.top
            val lineBottom = line?.bounds?.bottom ?: wordsOnLine.first().bounds.bottom

            val left = wordsOnLine.first().bounds.left
            val right = wordsOnLine.last().bounds.right

            // Tambahkan padding mikro agar sorotan teks tampak proporsional
            val strip = RectF(
                (left - 0.003f).coerceAtLeast(0f),
                (lineTop - 0.002f).coerceAtLeast(0f),
                (right + 0.003f).coerceAtMost(1f),
                (lineBottom + 0.002f).coerceAtMost(1f)
            )
            strips.add(strip)
        }

        return Pair(selectedText, strips)
    }

    /**
     * Fallback perhitungan strip baris jika data teks OCR belum tersedia
     */
    fun computeFallbackStrips(
        startNorm: PointF,
        endNorm: PointF
    ): List<RectF> {
        val top = min(startNorm.y, endNorm.y)
        val bottom = max(startNorm.y, endNorm.y)
        val left = min(startNorm.x, endNorm.x)
        val right = max(startNorm.x, endNorm.x)

        val height = bottom - top
        val estLineH = 0.022f // ~2.2% tinggi halaman per baris teks

        if (height <= estLineH * 1.4f) {
            return listOf(RectF(left, top, right, bottom))
        }

        val strips = mutableListOf<RectF>()
        val isDownwards = startNorm.y <= endNorm.y
        val firstX = if (isDownwards) startNorm.x else endNorm.x
        val lastX = if (isDownwards) endNorm.x else startNorm.x

        val rightMargin = 0.88f.coerceAtLeast(firstX + 0.1f)
        val leftMargin = 0.12f

        // Baris pertama
        val firstLineBottom = min(top + estLineH, bottom)
        strips.add(RectF(firstX, top, rightMargin, firstLineBottom))

        // Baris tengah
        var curY = top + estLineH
        while (curY + estLineH < bottom) {
            strips.add(RectF(leftMargin, curY, rightMargin, curY + estLineH))
            curY += estLineH
        }

        // Baris terakhir
        if (curY < bottom) {
            strips.add(RectF(leftMargin, curY, lastX.coerceAtLeast(leftMargin + 0.08f), bottom))
        }

        return strips
    }

    fun clear() {
        pageCache.evictAll()
    }
}
