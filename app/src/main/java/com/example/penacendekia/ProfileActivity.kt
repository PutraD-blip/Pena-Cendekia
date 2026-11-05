package com.example.penacendekia

import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.card.MaterialCardView

class ProfileActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView

    companion object {
        // URL login dashboard penulis di Laravel
        // Ubah sesuai domain atau port server Laravel Anda (misal: "https://penacendekia.com/author/login" atau "http://10.0.2.2:8000/author/login")
        const val AUTHOR_DASHBOARD_URL = "http://10.0.2.2:8000/author/login"
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_profile)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutProfileHeader)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(v.paddingLeft, systemBars.top, v.paddingRight, v.paddingBottom)
            insets
        }

        // Tapping "Mau Jadi Penulis? / Yuk Daftar!" opens Laravel author dashboard URL
        findViewById<MaterialCardView>(R.id.cardBecomeAuthor)?.setOnClickListener {
            openAuthorDashboard()
        }

        bottomNav = findViewById(R.id.bottomNavigationProfile)

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
                R.id.nav_profil -> true
                else -> false
            }
        }

        bottomNav.selectedItemId = R.id.nav_profil
    }

    private fun openAuthorDashboard() {
        try {
            val intent = Intent(Intent.ACTION_VIEW, Uri.parse(AUTHOR_DASHBOARD_URL))
            startActivity(intent)
        } catch (e: Exception) {
            Toast.makeText(this, "Tidak dapat membuka browser", Toast.LENGTH_SHORT).show()
        }
    }

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.selectedItemId = R.id.nav_profil
        }
    }
}
