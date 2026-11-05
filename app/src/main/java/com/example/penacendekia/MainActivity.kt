package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.widget.LinearLayout
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.card.MaterialCardView

class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_main)
        
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.main)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // 1. Tapping Book Card opens BookDetailActivity
        findViewById<LinearLayout>(R.id.layoutBookItem1)?.setOnClickListener {
            val intent = Intent(this, BookDetailActivity::class.java)
            startActivity(intent)
        }

        // 2. Tapping Search Bar opens BookDetailActivity
        findViewById<MaterialCardView>(R.id.cardSearch)?.setOnClickListener {
            val intent = Intent(this, BookDetailActivity::class.java)
            startActivity(intent)
        }

        // 3. Tapping Promo Banner opens CatalogActivity
        findViewById<MaterialCardView>(R.id.cardBanner)?.setOnClickListener {
            val intent = Intent(this, CatalogActivity::class.java)
            startActivity(intent)
        }

        // 4. Bottom Navigation Logic
        findViewById<BottomNavigationView>(R.id.bottomNavigation)?.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_beranda -> true
                R.id.nav_katalog -> {
                    startActivity(Intent(this, CatalogActivity::class.java))
                    true
                }
                R.id.nav_rak -> {
                    startActivity(Intent(this, ShelfActivity::class.java))
                    true
                }
                R.id.nav_profil -> {
                    startActivity(Intent(this, ProfileActivity::class.java))
                    true
                }
                else -> false
            }
        }
    }
}
