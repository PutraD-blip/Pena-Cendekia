package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.widget.ImageView
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.google.android.material.bottomnavigation.BottomNavigationView

class HistoryFavoriteActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_history_favorite)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.toolbarHistory)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        // Back button action
        findViewById<ImageView>(R.id.btnBackHistory)?.setOnClickListener {
            finish()
        }

        // Search icon action
        findViewById<ImageView>(R.id.btnSearchHistory)?.setOnClickListener {
            startActivity(Intent(this, SearchActivity::class.java))
        }

        // Tulis ulasan actions
        findViewById<android.widget.LinearLayout>(R.id.actionWriteReview1)?.setOnClickListener {
            startActivity(Intent(this, ReviewActivity::class.java))
        }
        findViewById<android.widget.LinearLayout>(R.id.actionWriteReview2)?.setOnClickListener {
            startActivity(Intent(this, ReviewActivity::class.java))
        }

        bottomNav = findViewById(R.id.bottomNavigationHistory)

        // Bottom Navigation Listener
        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_beranda -> {
                    startActivity(Intent(this, MainActivity::class.java))
                    finish()
                    true
                }
                R.id.nav_katalog -> {
                    startActivity(Intent(this, CatalogActivity::class.java))
                    finish()
                    true
                }
                R.id.nav_rak -> {
                    startActivity(Intent(this, ShelfActivity::class.java))
                    finish()
                    true
                }
                R.id.nav_profil -> {
                    startActivity(Intent(this, ProfileActivity::class.java))
                    true
                }
                else -> false
            }
        }

        bottomNav.selectedItemId = R.id.nav_rak
    }

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.selectedItemId = R.id.nav_rak
        }
    }
}
