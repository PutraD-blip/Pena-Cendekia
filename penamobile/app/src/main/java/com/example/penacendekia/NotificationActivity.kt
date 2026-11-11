package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.ProgressBar
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
import com.example.penacendekia.data.model.NotificationItem
import com.example.penacendekia.data.repository.BookCatalogRepository
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.card.MaterialCardView
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class NotificationActivity : AppCompatActivity() {

    private lateinit var layoutList: LinearLayout
    private lateinit var pbLoading: ProgressBar
    private lateinit var layoutEmpty: LinearLayout

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_notification)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.toolbarNotification)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            val lp = v.layoutParams as? android.view.ViewGroup.MarginLayoutParams
            if (lp != null && lp.topMargin != systemBars.top) {
                lp.topMargin = systemBars.top
                v.layoutParams = lp
            }
            insets
        }

        findViewById<ImageView>(R.id.btnBackNotification)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@NotificationActivity)
        }

        findViewById<TextView>(R.id.btnClearNotifications)?.setOnClickListener {
            Toast.makeText(this, "Semua notifikasi ditandai telah dibaca", Toast.LENGTH_SHORT).show()
        }

        layoutList = findViewById(R.id.layoutNotificationList)
        pbLoading = findViewById(R.id.pbLoadingNotifications)
        layoutEmpty = findViewById(R.id.layoutEmptyNotifications)

        loadNotifications()
    }

    private fun loadNotifications() {
        pbLoading.visibility = View.VISIBLE
        layoutEmpty.visibility = View.GONE
        layoutList.removeAllViews()

        RetrofitClient.apiService.getNotifications().enqueue(object : Callback<ApiResponse<List<NotificationItem>>> {
            override fun onResponse(
                call: Call<ApiResponse<List<NotificationItem>>>,
                response: Response<ApiResponse<List<NotificationItem>>>
            ) {
                pbLoading.visibility = View.GONE
                if (response.isSuccessful && response.body()?.success == true) {
                    val items = response.body()?.data ?: emptyList()
                    if (items.isEmpty()) {
                        layoutEmpty.visibility = View.VISIBLE
                    } else {
                        items.forEachIndexed { index, notif ->
                            val card = createNotificationCard(notif)
                            layoutList.addView(card)
                            PageTransition.animateViewIn(card, delayMs = index * 40L)
                        }
                    }
                } else {
                    layoutEmpty.visibility = View.VISIBLE
                }
            }

            override fun onFailure(call: Call<ApiResponse<List<NotificationItem>>>, t: Throwable) {
                pbLoading.visibility = View.GONE
                layoutEmpty.visibility = View.VISIBLE
            }
        })
    }

    private fun createNotificationCard(notif: NotificationItem): View {
        val dp = { value: Int -> (value * resources.displayMetrics.density).toInt() }

        val card = MaterialCardView(this).apply {
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply {
                bottomMargin = dp(12)
            }
            radius = dp(14).toFloat()
            cardElevation = dp(1).toFloat()
            strokeWidth = dp(1)
            strokeColor = getColor(R.color.divider_color)
            setCardBackgroundColor(getColor(R.color.card_bg))
            isClickable = true
            isFocusable = true
        }

        val row = LinearLayout(this).apply {
            orientation = LinearLayout.HORIZONTAL
            setPadding(dp(14), dp(14), dp(14), dp(14))
        }

        // Thumbnail Cover
        val ivCover = ImageView(this).apply {
            layoutParams = LinearLayout.LayoutParams(dp(54), dp(76)).apply {
                marginEnd = dp(14)
            }
            scaleType = ImageView.ScaleType.CENTER_CROP
            setBackgroundResource(R.drawable.bg_filter_box)
        }

        BookCoverResolver.loadCover(ivCover, notif.title, notif.cover_url)

        // Text content
        val colText = LinearLayout(this).apply {
            layoutParams = LinearLayout.LayoutParams(0, ViewGroup.LayoutParams.WRAP_CONTENT, 1f)
            orientation = LinearLayout.VERTICAL
        }

        val tvBadge = TextView(this).apply {
            text = "★ RILIS BUKU BARU · ${notif.date}"
            textSize = 10.5f
            setTextColor(getColor(R.color.accent_gold))
            typeface = android.graphics.Typeface.DEFAULT_BOLD
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { bottomMargin = dp(4) }
        }

        val tvTitle = TextView(this).apply {
            text = notif.title
            textSize = 14f
            setTextColor(getColor(R.color.primary_green))
            typeface = android.graphics.Typeface.DEFAULT_BOLD
            maxLines = 2
            ellipsize = android.text.TextUtils.TruncateAt.END
        }

        val tvMsg = TextView(this).apply {
            text = notif.message
            textSize = 12f
            setTextColor(getColor(R.color.text_muted))
            maxLines = 2
            ellipsize = android.text.TextUtils.TruncateAt.END
            layoutParams = LinearLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT
            ).apply { topMargin = dp(4) }
        }

        colText.addView(tvBadge)
        colText.addView(tvTitle)
        colText.addView(tvMsg)

        row.addView(ivCover)
        row.addView(colText)
        card.addView(row)

        card.setOnClickListener {
            // Langsung buka detail buku
            val rawBook = Book(
                id = notif.book_id,
                title = notif.title.replace("E-Book Baru Dirilis: ", "").replace("Buku Cetak Baru: ", "").replace("Buku Baru Ditambahkan: ", ""),
                author = notif.author,
                category = notif.category,
                cover_url = notif.cover_url
            )
            val book = BookCatalogRepository.enrichBook(rawBook)
            val intent = Intent(this, BookDetailActivity::class.java).apply {
                putExtra("BOOK_ID", notif.book_id)
                putExtra("BOOK_OBJECT", book)
                putExtra(ReaderActivity.EXTRA_BOOK, book)
            }
            PageTransition.slideForward(this, intent)
        }

        return card
    }
}
