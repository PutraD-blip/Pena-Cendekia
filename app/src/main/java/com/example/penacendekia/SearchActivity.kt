package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.widget.EditText
import android.widget.ImageView
import android.widget.LinearLayout
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.google.android.material.bottomnavigation.BottomNavigationView

class SearchActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_search)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutSearchHeader)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        // Back button action
        findViewById<ImageView>(R.id.btnBackSearch)?.setOnClickListener {
            finish()
        }

        // Clear search input action
        findViewById<ImageView>(R.id.btnClearSearch)?.setOnClickListener {
            findViewById<EditText>(R.id.etSearchQuery)?.setText("")
        }

        // Tapping search result book opens BookDetailActivity
        findViewById<LinearLayout>(R.id.layoutSearchResultBook)?.setOnClickListener {
            startActivity(Intent(this, BookDetailActivity::class.java))
        }

        // Tapping related book opens BookDetailActivity
        findViewById<LinearLayout>(R.id.layoutRelatedBook)?.setOnClickListener {
            startActivity(Intent(this, BookDetailActivity::class.java))
        }

        bottomNav = findViewById(R.id.bottomNavigationSearch)
        bottomNav.selectedItemId = R.id.nav_katalog

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

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.selectedItemId = R.id.nav_katalog
        }
    }
}
