package com.example.penacendekia

import android.content.Intent
import android.os.Build
import android.os.Bundle
import android.view.View
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import androidx.activity.addCallback
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.bumptech.glide.Glide
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.repository.BookCatalogRepository
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.card.MaterialCardView

class PaymentActivity : AppCompatActivity() {

    private enum class PaymentMethod {
        QRIS, VA, WALLET
    }

    private var selectedMethod = PaymentMethod.QRIS
    private var targetBook: Book? = null

    companion object {
        const val EXTRA_BOOK = "EXTRA_BOOK"
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_payment)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutPaymentRoot)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // Retrieve book from intent if passed
        targetBook = getBookFromIntent()?.let { BookCatalogRepository.enrichBook(it) }

        // Bind book data
        bindBookData()

        // Back button
        findViewById<ImageView>(R.id.btnBackPayment)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@PaymentActivity)
        }

        val cardQris = findViewById<MaterialCardView>(R.id.cardQris)
        val cardVa = findViewById<MaterialCardView>(R.id.cardVa)
        val cardWallet = findViewById<MaterialCardView>(R.id.cardWallet)

        val radioQris = findViewById<ImageView>(R.id.radioQris)
        val radioVa = findViewById<ImageView>(R.id.radioVa)
        val radioWallet = findViewById<ImageView>(R.id.radioWallet)
        val tvQrisNote = findViewById<TextView>(R.id.tvQrisNote)

        fun updatePaymentSelection(method: PaymentMethod) {
            selectedMethod = method

            val outlineGreen = ContextCompat.getColor(this, R.color.outline_green)
            val dividerColor = ContextCompat.getColor(this, R.color.divider_color)
            val bgSelected = ContextCompat.getColor(this, R.color.bg_pay_selected)
            val bgCard = ContextCompat.getColor(this, R.color.card_bg)

            val strokeSelectedWidth = (1.5f * resources.displayMetrics.density).toInt()
            val strokeNormalWidth = (1f * resources.displayMetrics.density).toInt()

            // QRIS
            val isQris = method == PaymentMethod.QRIS
            cardQris?.setCardBackgroundColor(if (isQris) bgSelected else bgCard)
            cardQris?.strokeColor = if (isQris) outlineGreen else dividerColor
            cardQris?.strokeWidth = if (isQris) strokeSelectedWidth else strokeNormalWidth
            radioQris?.setImageResource(if (isQris) R.drawable.ic_radio_dot else R.drawable.ic_radio_unselected)
            tvQrisNote?.visibility = if (isQris) View.VISIBLE else View.GONE

            // VA
            val isVa = method == PaymentMethod.VA
            cardVa?.setCardBackgroundColor(if (isVa) bgSelected else bgCard)
            cardVa?.strokeColor = if (isVa) outlineGreen else dividerColor
            cardVa?.strokeWidth = if (isVa) strokeSelectedWidth else strokeNormalWidth
            radioVa?.setImageResource(if (isVa) R.drawable.ic_radio_dot else R.drawable.ic_radio_unselected)

            // Wallet
            val isWallet = method == PaymentMethod.WALLET
            cardWallet?.setCardBackgroundColor(if (isWallet) bgSelected else bgCard)
            cardWallet?.strokeColor = if (isWallet) outlineGreen else dividerColor
            cardWallet?.strokeWidth = if (isWallet) strokeSelectedWidth else strokeNormalWidth
            radioWallet?.setImageResource(if (isWallet) R.drawable.ic_radio_dot else R.drawable.ic_radio_unselected)
        }

        cardQris?.setOnClickListener { updatePaymentSelection(PaymentMethod.QRIS) }
        cardVa?.setOnClickListener { updatePaymentSelection(PaymentMethod.VA) }
        cardWallet?.setOnClickListener { updatePaymentSelection(PaymentMethod.WALLET) }

        // Pay now button
        findViewById<MaterialCardView>(R.id.btnPayNow)?.setOnClickListener {
            val bookTitle = targetBook?.title ?: "Kepemimpinan Visioner"
            Toast.makeText(this, "Pembayaran berhasil! '$bookTitle' telah ditambahkan ke Rak Saya.", Toast.LENGTH_LONG).show()
            PageTransition.switchTab(this, ShelfActivity::class.java)
        }
    }

    private fun bindBookData() {
        val book = targetBook ?: return

        findViewById<TextView>(R.id.tvPaymentBookTitle)?.text = book.title
        findViewById<TextView>(R.id.tvPaymentBookAuthor)?.text = book.author

        val typeText = if (book.type.equals("ebook", ignoreCase = true)) "E-BOOK DIGITAL" else "BUKU DIGITAL"
        findViewById<TextView>(R.id.tvPaymentBookType)?.text = typeText

        val coverImageView = findViewById<ImageView>(R.id.ivPaymentBookCover)
        if (coverImageView != null) {
            BookCoverResolver.loadCover(coverImageView, book)
        }

        val priceText = book.formatted_price ?: when {
            book.price > 0 -> "Rp ${String.format("%,d", book.price.toLong()).replace(',', '.')}"
            else -> "Rp 59.000"
        }

        findViewById<TextView>(R.id.tvPaymentBookPrice)?.text = priceText
        findViewById<TextView>(R.id.tvPaymentTotalPrice)?.text = priceText
        findViewById<TextView>(R.id.tvPayNowText)?.text = "Bayar sekarang · $priceText"
    }

    private fun getBookFromIntent(): Book? = try {
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.TIRAMISU) {
            intent.getSerializableExtra(EXTRA_BOOK, Book::class.java)
        } else {
            @Suppress("DEPRECATION")
            intent.getSerializableExtra(EXTRA_BOOK) as? Book
        }
    } catch (e: Exception) {
        null
    }
}
