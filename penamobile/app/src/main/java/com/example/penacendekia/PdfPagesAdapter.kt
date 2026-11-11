package com.example.penacendekia

import android.content.Context
import android.graphics.Bitmap
import android.graphics.Color
import android.graphics.RectF
import android.graphics.pdf.PdfRenderer
import android.os.Handler
import android.os.Looper
import android.util.LruCache
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.penacendekia.data.model.Book
import java.util.concurrent.Executors

class PdfPagesAdapter(
    private val context: Context,
    private val renderer: PdfRenderer,
    private val book: Book,
    private val onHighlightCreated: ((page: Int, selectedText: String, lineStrips: List<RectF>) -> Unit)? = null,
    private val onHighlightClicked: ((highlight: SavedHighlightNote) -> Unit)? = null,
    private val onPageBookmarkRequested: ((page: Int) -> Unit)? = null
) : RecyclerView.Adapter<PdfPagesAdapter.PageViewHolder>() {

    // Cache up to 15 rendered pages in memory for buttery-smooth scrolling
    private val bitmapCache = LruCache<Int, Bitmap>(15)
    private val executor = Executors.newFixedThreadPool(2)
    private val mainHandler = Handler(Looper.getMainLooper())

    inner class PageViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val ivPage: ZoomableImageView = itemView.findViewById(R.id.ivPdfPage)
        val tvPageNumber: TextView = itemView.findViewById(R.id.tvPdfPageNumber)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): PageViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.item_pdf_page, parent, false)
        return PageViewHolder(view)
    }

    override fun getItemCount(): Int = renderer.pageCount

    override fun onBindViewHolder(holder: PageViewHolder, position: Int) {
        val pageNum = position + 1
        holder.tvPageNumber.text = "— Halaman $pageNum dari ${renderer.pageCount} —"
        holder.ivPage.pageIndex = pageNum
        holder.ivPage.onHighlightCreated = onHighlightCreated
        holder.ivPage.onHighlightClicked = onHighlightClicked
        holder.ivPage.onPageBookmarkRequested = onPageBookmarkRequested

        // Hubungkan data teks OCR yang sudah ter-cache (jika ada)
        holder.ivPage.pageTextData = PdfPageTextManager.getCachedText(pageNum)

        // Muat sorotan & catatan untuk halaman ini
        val highlights = BookActivityStore.getHighlightsForPage(context, book, pageNum)
        holder.ivPage.setHighlights(highlights)

        val cached = bitmapCache.get(position)
        if (cached != null) {
            holder.ivPage.setImageBitmap(cached)
            PdfPageTextManager.extractText(pageNum, cached) { textData ->
                mainHandler.post {
                    if (holder.bindingAdapterPosition == position) {
                        holder.ivPage.pageTextData = textData
                    }
                }
            }
            return
        }

        holder.ivPage.setImageDrawable(null)
        val targetPos = position

        executor.execute {
            val bitmap = renderPageBitmap(targetPos)
            if (bitmap != null) {
                bitmapCache.put(targetPos, bitmap)
                mainHandler.post {
                    if (holder.bindingAdapterPosition == targetPos) {
                        holder.ivPage.setImageBitmap(bitmap)
                    }
                }
                PdfPageTextManager.extractText(pageNum, bitmap) { textData ->
                    mainHandler.post {
                        if (holder.bindingAdapterPosition == targetPos) {
                            holder.ivPage.pageTextData = textData
                        }
                    }
                }
            }
        }
    }

    private fun renderPageBitmap(pageIndex: Int): Bitmap? {
        return try {
            synchronized(renderer) {
                if (pageIndex < 0 || pageIndex >= renderer.pageCount) return null
                val page = renderer.openPage(pageIndex)

                val displayMetrics = context.resources.displayMetrics
                val baseWidth = (displayMetrics.widthPixels - (28 * displayMetrics.density).toInt()).coerceAtLeast(400)
                // Render resolusi tajam (1.35x) agar saat pembaca mencubit layar (pinch-to-zoom) tulisan tetap tajam
                val targetWidth = (baseWidth * 1.35f).toInt()
                val scale = targetWidth.toFloat() / page.width.toFloat()
                val targetHeight = (page.height * scale).toInt().coerceAtLeast(600)

                val bitmap = Bitmap.createBitmap(targetWidth, targetHeight, Bitmap.Config.ARGB_8888)
                val canvas = android.graphics.Canvas(bitmap)
                canvas.drawColor(Color.WHITE)

                page.render(bitmap, null, null, PdfRenderer.Page.RENDER_MODE_FOR_DISPLAY)
                page.close()
                bitmap
            }
        } catch (e: Exception) {
            e.printStackTrace()
            null
        }
    }

    fun refreshHighlights() {
        notifyDataSetChanged()
    }

    fun clear() {
        bitmapCache.evictAll()
    }
}
