package com.example.penacendekia

import android.graphics.Color
import android.os.Bundle
import android.widget.ImageView
import android.widget.RelativeLayout
import android.widget.SeekBar
import android.widget.TextView
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.google.android.material.card.MaterialCardView

class ReaderAppearanceActivity : AppCompatActivity() {

    private lateinit var layoutAppearanceRoot: RelativeLayout
    private lateinit var toolbarAppearance: RelativeLayout
    private lateinit var cardAppearanceSheet: MaterialCardView

    private lateinit var tvBookTitleAppearance: TextView
    private lateinit var tvSubtitleAppearance: TextView
    private lateinit var tvChapterTitleAppearance: TextView
    private lateinit var tvParagraphAppearance: TextView
    private lateinit var tvPageCenterAppearance: TextView
    private lateinit var tvFontSizeValue: TextView

    private lateinit var cardThemeTerang: MaterialCardView
    private lateinit var cardThemeGelap: MaterialCardView
    private lateinit var cardThemeSepia: MaterialCardView

    private lateinit var tvAaTerang: TextView
    private lateinit var tvAaGelap: TextView
    private lateinit var tvAaSepia: TextView

    private lateinit var tvLabelTerang: TextView
    private lateinit var tvLabelGelap: TextView
    private lateinit var tvLabelSepia: TextView

    private lateinit var btnBackAppearance: ImageView
    private lateinit var btnBookmarkAppearance: ImageView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_reader_appearance)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.toolbarAppearance)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        // View Bindings
        layoutAppearanceRoot = findViewById(R.id.layoutAppearanceRoot)
        toolbarAppearance = findViewById(R.id.toolbarAppearance)
        cardAppearanceSheet = findViewById(R.id.cardAppearanceSheet)

        tvBookTitleAppearance = findViewById(R.id.tvBookTitleAppearance)
        tvSubtitleAppearance = findViewById(R.id.tvSubtitleAppearance)
        tvChapterTitleAppearance = findViewById(R.id.tvChapterTitleAppearance)
        tvParagraphAppearance = findViewById(R.id.tvParagraphAppearance)
        tvPageCenterAppearance = findViewById(R.id.tvPageCenterAppearance)
        tvFontSizeValue = findViewById(R.id.tvFontSizeValue)

        cardThemeTerang = findViewById(R.id.cardThemeTerang)
        cardThemeGelap = findViewById(R.id.cardThemeGelap)
        cardThemeSepia = findViewById(R.id.cardThemeSepia)

        tvAaTerang = findViewById(R.id.tvAaTerang)
        tvAaGelap = findViewById(R.id.tvAaGelap)
        tvAaSepia = findViewById(R.id.tvAaSepia)

        tvLabelTerang = findViewById(R.id.tvLabelTerang)
        tvLabelGelap = findViewById(R.id.tvLabelGelap)
        tvLabelSepia = findViewById(R.id.tvLabelSepia)

        btnBackAppearance = findViewById(R.id.btnBackAppearance)
        btnBookmarkAppearance = findViewById(R.id.btnBookmarkAppearance)

        // Back action
        btnBackAppearance.setOnClickListener { finish() }

        // Done button closes activity/sheet
        findViewById<TextView>(R.id.btnDoneAppearance)?.setOnClickListener {
            finish()
        }

        // Seek bar for font size
        findViewById<SeekBar>(R.id.seekBarFontSize)?.setOnSeekBarChangeListener(object : SeekBar.OnSeekBarChangeListener {
            override fun onProgressChanged(seekBar: SeekBar?, progress: Int, fromUser: Boolean) {
                val size = if (progress < 12) 12 else progress
                tvFontSizeValue.text = "$size px"
                tvParagraphAppearance.textSize = size.toFloat()
            }
            override fun onStartTrackingTouch(seekBar: SeekBar?) {}
            override fun onStopTrackingTouch(seekBar: SeekBar?) {}
        })

        // Theme Switchers
        cardThemeTerang.setOnClickListener { applyThemeMode("TERANG") }
        cardThemeGelap.setOnClickListener { applyThemeMode("GELAP") }
        cardThemeSepia.setOnClickListener { applyThemeMode("SEPIA") }

        // Default: Sepia (matches screenshot)
        applyThemeMode("SEPIA")
    }

    private fun applyThemeMode(theme: String) {
        val colorTerangFont = Color.parseColor("#203C32")
        val colorGelapBg = Color.parseColor("#222B26")
        val colorGelapFont = Color.parseColor("#FFFFFF")
        val colorSepiaFont = Color.parseColor("#584A35")
        val dividerColor = Color.parseColor("#E0DBD0")

        when (theme) {
            "TERANG" -> {
                val bgLight = Color.parseColor("#F8F6EF")
                layoutAppearanceRoot.setBackgroundColor(bgLight)

                tvBookTitleAppearance.setTextColor(colorTerangFont)
                tvSubtitleAppearance.text = "Pratinjau tema terang · halaman 42"
                tvSubtitleAppearance.setTextColor(Color.parseColor("#68766E"))

                tvChapterTitleAppearance.setTextColor(colorTerangFont)
                tvParagraphAppearance.setTextColor(colorTerangFont)
                tvPageCenterAppearance.setTextColor(Color.parseColor("#68766E"))

                btnBackAppearance.setColorFilter(colorTerangFont)
                btnBookmarkAppearance.setColorFilter(colorTerangFont)

                // Theme Cards Highlights
                cardThemeTerang.setStrokeColor(colorTerangFont)
                cardThemeTerang.strokeWidth = 3
                tvLabelTerang.text = "✓ Terang"

                cardThemeGelap.setStrokeColor(Color.TRANSPARENT)
                cardThemeGelap.strokeWidth = 0
                tvLabelGelap.text = "Gelap"

                cardThemeSepia.setStrokeColor(dividerColor)
                cardThemeSepia.strokeWidth = 2
                tvLabelSepia.text = "Sepia"
            }
            "GELAP" -> {
                layoutAppearanceRoot.setBackgroundColor(colorGelapBg)

                tvBookTitleAppearance.setTextColor(colorGelapFont)
                tvSubtitleAppearance.text = "Pratinjau tema gelap · halaman 42"
                tvSubtitleAppearance.setTextColor(Color.parseColor("#A0AFA6"))

                tvChapterTitleAppearance.setTextColor(colorGelapFont)
                tvParagraphAppearance.setTextColor(colorGelapFont)
                tvPageCenterAppearance.setTextColor(Color.parseColor("#A0AFA6"))

                btnBackAppearance.setColorFilter(colorGelapFont)
                btnBookmarkAppearance.setColorFilter(colorGelapFont)

                // Theme Cards Highlights
                cardThemeTerang.setStrokeColor(dividerColor)
                cardThemeTerang.strokeWidth = 2
                tvLabelTerang.text = "Terang"

                cardThemeGelap.setStrokeColor(colorGelapFont)
                cardThemeGelap.strokeWidth = 3
                tvLabelGelap.text = "✓ Gelap"

                cardThemeSepia.setStrokeColor(dividerColor)
                cardThemeSepia.strokeWidth = 2
                tvLabelSepia.text = "Sepia"
            }
            "SEPIA" -> {
                val bgSepia = Color.parseColor("#F4E8D3")
                val textSepiaMuted = Color.parseColor("#7A6A55")

                layoutAppearanceRoot.setBackgroundColor(bgSepia)

                tvBookTitleAppearance.setTextColor(colorTerangFont)
                tvSubtitleAppearance.text = "Pratinjau tema sepia · halaman 42"
                tvSubtitleAppearance.setTextColor(textSepiaMuted)

                tvChapterTitleAppearance.setTextColor(colorTerangFont)
                tvParagraphAppearance.setTextColor(colorTerangFont)
                tvPageCenterAppearance.setTextColor(textSepiaMuted)

                btnBackAppearance.setColorFilter(colorTerangFont)
                btnBookmarkAppearance.setColorFilter(colorSepiaFont)

                // Theme Cards Highlights
                cardThemeTerang.setStrokeColor(dividerColor)
                cardThemeTerang.strokeWidth = 2
                tvLabelTerang.text = "Terang"

                cardThemeGelap.setStrokeColor(Color.TRANSPARENT)
                cardThemeGelap.strokeWidth = 0
                tvLabelGelap.text = "Gelap"

                cardThemeSepia.setStrokeColor(colorTerangFont)
                cardThemeSepia.strokeWidth = 3
                tvLabelSepia.text = "✓ Sepia"
            }
        }
    }
}
