package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.google.android.material.card.MaterialCardView

class PaymentActivity : AppCompatActivity() {

    private enum class PaymentMethod {
        QRIS, VA, WALLET
    }

    private var selectedMethod = PaymentMethod.QRIS

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_payment)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutPaymentRoot)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // Back button
        findViewById<ImageView>(R.id.btnBackPayment)?.setOnClickListener {
            finish()
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
            Toast.makeText(this, "Pembayaran berhasil! Buku masuk ke Rak Anda.", Toast.LENGTH_LONG).show()
            val intent = Intent(this, ShelfActivity::class.java)
            intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP
            startActivity(intent)
            finish()
        }
    }
}
