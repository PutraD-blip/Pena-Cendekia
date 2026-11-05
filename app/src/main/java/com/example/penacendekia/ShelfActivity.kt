package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.ImageView
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.card.MaterialCardView

class ShelfActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_shelf)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutShelfHeader)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        // Tapping history icon opens HistoryFavoriteActivity
        findViewById<ImageView>(R.id.ivHistoryShelf)?.setOnClickListener {
            startActivity(Intent(this, HistoryFavoriteActivity::class.java))
        }

        // Tapping book card opens ShelfBookDetailActivity (Detail buku sesi aktif)
        findViewById<MaterialCardView>(R.id.cardShelfBook1)?.setOnClickListener {
            startActivity(Intent(this, ShelfBookDetailActivity::class.java))
        }

        findViewById<MaterialCardView>(R.id.cardShelfBook2)?.setOnClickListener {
            startActivity(Intent(this, ShelfBookDetailActivity::class.java))
        }

        // "Lanjut membaca" buttons open ReaderActivity directly
        findViewById<Button>(R.id.btnReadBook1)?.setOnClickListener {
            startActivity(Intent(this, ReaderActivity::class.java))
        }

        findViewById<Button>(R.id.btnReadBook2)?.setOnClickListener {
            startActivity(Intent(this, ReaderActivity::class.java))
        }

        bottomNav = findViewById(R.id.bottomNavigationShelf)

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
                R.id.nav_rak -> true
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
