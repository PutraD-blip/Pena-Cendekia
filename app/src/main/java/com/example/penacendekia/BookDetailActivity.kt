package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.widget.ImageView
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.google.android.material.bottomnavigation.BottomNavigationView

class BookDetailActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView
    private var isFromShelf: Boolean = true

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_book_detail)

        isFromShelf = intent.getBooleanExtra("FROM_SHELF", true)
        
        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.toolbarDetail)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        // Back button action
        findViewById<ImageView>(R.id.btnBackDetail)?.setOnClickListener {
            finish()
        }

        // Navigate to ReaderActivity when clicking action read or buy
        findViewById<android.view.View>(R.id.btnActionRead)?.setOnClickListener {
            val intent = Intent(this, ReaderActivity::class.java)
            startActivity(intent)
        }

        findViewById<android.view.View>(R.id.btnActionBuy)?.setOnClickListener {
            val intent = Intent(this, PaymentActivity::class.java)
            startActivity(intent)
        }

        bottomNav = findViewById(R.id.bottomNavigationDetail)

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

        bottomNav.selectedItemId = if (isFromShelf) R.id.nav_rak else R.id.nav_katalog
    }

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.selectedItemId = if (isFromShelf) R.id.nav_rak else R.id.nav_katalog
        }
    }
}
