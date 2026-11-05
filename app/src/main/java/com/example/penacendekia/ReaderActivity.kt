package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.widget.ImageView
import android.widget.LinearLayout
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

class ReaderActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_reader)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.toolbarReader)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        // Back button action
        findViewById<ImageView>(R.id.btnBackReader)?.setOnClickListener {
            finish()
        }

        // Tampilan button action -> opens ReaderAppearanceActivity
        findViewById<LinearLayout>(R.id.btnAppearance)?.setOnClickListener {
            startActivity(Intent(this, ReaderAppearanceActivity::class.java))
        }

        // Catatan button action -> opens ReaderNotesActivity
        findViewById<LinearLayout>(R.id.btnNotes)?.setOnClickListener {
            startActivity(Intent(this, ReaderNotesActivity::class.java))
        }
    }
}
