package com.example.penacendekia

import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.ImageView
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat

class ReaderNotesActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_reader_notes)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.toolbarNotes)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        // Back button action
        findViewById<ImageView>(R.id.btnBackNotes)?.setOnClickListener {
            finish()
        }

        // Save note action
        findViewById<Button>(R.id.btnSaveNote)?.setOnClickListener {
            val text = findViewById<EditText>(R.id.etNoteContent)?.text.toString()
            if (text.isNotEmpty()) {
                Toast.makeText(this, "Catatan berhasil disimpan", Toast.LENGTH_SHORT).show()
            }
            finish()
        }
    }
}
