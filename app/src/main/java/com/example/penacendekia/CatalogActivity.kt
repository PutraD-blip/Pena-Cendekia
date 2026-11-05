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

class CatalogActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_catalog)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutCatalogHeader)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        // Tapping search card opens SearchActivity
        findViewById<MaterialCardView>(R.id.cardSearchCatalog)?.setOnClickListener {
            startActivity(Intent(this, SearchActivity::class.java))
        }

        // Tapping book 1 opens BookDetailActivity
        findViewById<LinearLayout>(R.id.catalogBook1)?.setOnClickListener {
            startActivity(Intent(this, BookDetailActivity::class.java))
        }

        bottomNav = findViewById(R.id.bottomNavigationCatalog)

        // Bottom Navigation Listener
        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_beranda -> {
                    startActivity(Intent(this, MainActivity::class.java))
                    finish()
                    true
                }
                R.id.nav_katalog -> true
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

        // Ensure "Katalog" (search icon) is active
        bottomNav.selectedItemId = R.id.nav_katalog
    }

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.selectedItemId = R.id.nav_katalog
        }
    }
}
