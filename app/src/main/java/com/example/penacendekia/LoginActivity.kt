package com.example.penacendekia

import android.content.Intent
import android.graphics.Typeface
import android.os.Bundle
import android.text.SpannableString
import android.text.Spanned
import android.text.style.ForegroundColorSpan
import android.text.style.StyleSpan
import android.widget.TextView
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.google.android.material.card.MaterialCardView

class LoginActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_login)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutLoginRoot)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // Format footer terms & privacy text
        val tvTerms = findViewById<TextView>(R.id.tvTermsFooter)
        if (tvTerms != null) {
            val termsText = "Dengan melanjutkan, Anda menyetujui Syarat & Ketentuan serta Kebijakan Privasi Pena Cendekia."
            val spannableString = SpannableString(termsText)
            val primaryGreen = ContextCompat.getColor(this, R.color.primary_green)

            val termsStart = termsText.indexOf("Syarat & Ketentuan")
            if (termsStart != -1) {
                val termsEnd = termsStart + "Syarat & Ketentuan".length
                spannableString.setSpan(StyleSpan(Typeface.BOLD), termsStart, termsEnd, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE)
                spannableString.setSpan(ForegroundColorSpan(primaryGreen), termsStart, termsEnd, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE)
            }

            val privacyStart = termsText.indexOf("Kebijakan Privasi")
            if (privacyStart != -1) {
                val privacyEnd = privacyStart + "Kebijakan Privasi".length
                spannableString.setSpan(StyleSpan(Typeface.BOLD), privacyStart, privacyEnd, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE)
                spannableString.setSpan(ForegroundColorSpan(primaryGreen), privacyStart, privacyEnd, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE)
            }

            tvTerms.text = spannableString
        }

        // Handle Continue with Google button click -> opens MainActivity
        findViewById<MaterialCardView>(R.id.btnGoogleLogin)?.setOnClickListener {
            val intent = Intent(this, MainActivity::class.java)
            startActivity(intent)
            finish()
        }
    }
}
