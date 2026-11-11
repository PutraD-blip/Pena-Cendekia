package com.example.penacendekia

import android.app.AlertDialog
import android.content.Context
import android.content.Intent
import android.graphics.Color
import android.graphics.drawable.ColorDrawable
import android.net.Uri
import android.os.Bundle
import android.view.LayoutInflater
import android.widget.EditText
import android.widget.TextView
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.example.penacendekia.data.api.RetrofitClient
import com.example.penacendekia.ui.PageTransition
import com.google.android.material.bottomnavigation.BottomNavigationView
import com.google.android.material.card.MaterialCardView

class ProfileActivity : AppCompatActivity() {
    private lateinit var bottomNav: BottomNavigationView
    private lateinit var tvProfileName: TextView
    private lateinit var tvProfileEmail: TextView
    private lateinit var tvAvatarInitials: TextView
    private lateinit var tvCardHolderName: TextView
    private lateinit var tvCardMemberId: TextView
    private lateinit var tvStatFinishedCount: TextView
    private lateinit var tvStatFavoritesCount: TextView
    private lateinit var tvStatBookmarksCount: TextView

    companion object {
        const val AUTHOR_DASHBOARD_URL = "http://10.0.2.2:8000/author/login"
        const val PREFS_USER = "user_prefs"
        const val KEY_NAME = "user_name"
        const val KEY_EMAIL = "user_email"
        const val KEY_PHONE = "user_phone"
        const val KEY_BIO = "user_bio"
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

        // Bind Profile Identity Views
        tvProfileName = findViewById(R.id.tvProfileName)
        tvProfileEmail = findViewById(R.id.tvProfileEmail)
        tvAvatarInitials = findViewById(R.id.tvAvatarInitials)
        tvCardHolderName = findViewById(R.id.tvCardHolderName)
        tvCardMemberId = findViewById(R.id.tvCardMemberId)
        tvStatFinishedCount = findViewById(R.id.tvStatFinishedCount)
        tvStatFavoritesCount = findViewById(R.id.tvStatFavoritesCount)
        tvStatBookmarksCount = findViewById(R.id.tvStatBookmarksCount)

        loadUserProfile()
        loadReadingStats()

        // 1. User Profile Card & Edit Account Click
        val openEditProfile = { showEditProfileDialog() }
        findViewById<MaterialCardView>(R.id.cardUserProfile)?.setOnClickListener { openEditProfile() }
        findViewById<android.view.View>(R.id.btnAccountData)?.setOnClickListener { openEditProfile() }


        // 3. Quick Stats Cards -> Open Shelf / HistoryFavoriteActivity
        findViewById<MaterialCardView>(R.id.cardStatFinished)?.setOnClickListener {
            val intent = Intent(this, HistoryFavoriteActivity::class.java).apply {
                putExtra("SELECTED_TAB", 0)
            }
            PageTransition.slideForward(this, intent)
        }
        findViewById<MaterialCardView>(R.id.cardStatFavorites)?.setOnClickListener {
            val intent = Intent(this, HistoryFavoriteActivity::class.java).apply {
                putExtra("SELECTED_TAB", 1)
            }
            PageTransition.slideForward(this, intent)
        }
        findViewById<MaterialCardView>(R.id.cardStatBookmarks)?.setOnClickListener {
            val intent = Intent(this, HistoryFavoriteActivity::class.java).apply {
                putExtra("SELECTED_TAB", 2)
            }
            PageTransition.slideForward(this, intent)
        }

        // 4. Digital Member Card Click -> Shows Member Card Modal
        findViewById<MaterialCardView>(R.id.cardDigitalMember)?.setOnClickListener {
            showDigitalMemberCardDialog()
        }

        // 5. Mau Jadi Penulis Card Click -> Opens Author Dashboard
        findViewById<MaterialCardView>(R.id.cardBecomeAuthor)?.setOnClickListener {
            openAuthorDashboard()
        }

        // 6. Menu Rows: Aktivitas Saya
        findViewById<android.view.View>(R.id.rowShelfCollection)?.setOnClickListener {
            PageTransition.switchTab(this, ShelfActivity::class.java)
        }
        findViewById<android.view.View>(R.id.rowReadingHistory)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, HistoryFavoriteActivity::class.java))
        }
        findViewById<android.view.View>(R.id.rowNotifications)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, NotificationActivity::class.java))
        }
        findViewById<android.view.View>(R.id.rowMyReviews)?.setOnClickListener {
            showMyReviewsDialog()
        }

        // 7. Menu Rows: Akun & Keamanan
        findViewById<android.view.View>(R.id.btnChangePassword)?.setOnClickListener {
            showChangePasswordDialog()
        }
        findViewById<android.view.View>(R.id.btnNotificationPrivacy)?.setOnClickListener {
            showPrivacySecurityDialog()
        }

        // 8. Menu Rows: Bantuan & Lainnya
        findViewById<android.view.View>(R.id.btnHelpCenter)?.setOnClickListener {
            showHelpCenterDialog()
        }
        findViewById<android.view.View>(R.id.btnAboutPublisher)?.setOnClickListener {
            showAboutPublisherDialog()
        }

        // 9. Keluar dari Akun (Logout) with Confirmation
        findViewById<MaterialCardView>(R.id.btnLogoutProfile)?.setOnClickListener {
            showLogoutConfirmDialog()
        }

        // Bottom Navigation Bar
        bottomNav = findViewById(R.id.bottomNavigationProfile)
        bottomNav.setOnItemSelectedListener { item ->
            when (item.itemId) {
                R.id.nav_beranda -> {
                    PageTransition.switchTab(this, MainActivity::class.java)
                    true
                }
                R.id.nav_katalog -> {
                    PageTransition.switchTab(this, CatalogActivity::class.java)
                    true
                }
                R.id.nav_rak -> {
                    PageTransition.switchTab(this, ShelfActivity::class.java)
                    true
                }
                R.id.nav_profil -> true
                else -> false
            }
        }
        bottomNav.selectedItemId = R.id.nav_profil

        // Handle Back button to return to Beranda
        onBackPressedDispatcher.addCallback(this, object : androidx.activity.OnBackPressedCallback(true) {
            override fun handleOnBackPressed() {
                PageTransition.switchTab(this@ProfileActivity, MainActivity::class.java)
            }
        })
    }

    override fun onResume() {
        super.onResume()
        if (::bottomNav.isInitialized) {
            bottomNav.selectedItemId = R.id.nav_profil
        }
        loadUserProfile()
        loadReadingStats()
    }

    private fun loadUserProfile() {
        val name = UserSessionManager.getUserName(this)
        val email = UserSessionManager.getUserEmail(this)
        val memberId = UserSessionManager.getMemberId(this)
        val isAlya = email.trim().lowercase().contains("alya")

        tvProfileName.text = name
        tvProfileEmail.text = email
        tvCardHolderName.text = name
        tvCardMemberId.text = "ID: $memberId • Ketuk info"

        // Calculate initials from name
        val initials = if (isAlya) {
            "AP"
        } else {
            name.trim().split(Regex("\\s+"))
                .filter { it.isNotBlank() }
                .mapNotNull { it.firstOrNull()?.uppercaseChar() }
                .take(2)
                .joinToString("")
                .ifEmpty { email.firstOrNull { it.isLetter() }?.uppercase() ?: "P" }
        }
        tvAvatarInitials.text = initials
    }

    private fun loadReadingStats() {
        val bookmarks = BookActivityStore.getBookmarks(this)
        val favorites = BookActivityStore.getFavorites(this)
        val finished = BookActivityStore.getFinishedBooks(this)
        val email = UserSessionManager.getUserEmail(this).trim().lowercase()
        val isAlya = email.contains("alya")

        tvStatFinishedCount.text = if (isAlya) (finished.size.coerceAtLeast(18)).toString() else finished.size.toString()
        tvStatFavoritesCount.text = if (isAlya) (favorites.size.coerceAtLeast(5)).toString() else favorites.size.toString()
        tvStatBookmarksCount.text = if (isAlya) (bookmarks.size.coerceAtLeast(3)).toString() else bookmarks.size.toString()
    }

    private fun showEditProfileDialog() {
        val currentName = UserSessionManager.getUserName(this)
        val currentEmail = UserSessionManager.getUserEmail(this)
        val currentPhone = UserSessionManager.getUserPhone(this)

        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_edit_profile, null)
        val dialog = AlertDialog.Builder(this)
            .setView(dialogView)
            .create()

        dialog.window?.setBackgroundDrawable(ColorDrawable(Color.TRANSPARENT))

        val etName = dialogView.findViewById<EditText>(R.id.etEditProfileName)
        val etEmail = dialogView.findViewById<EditText>(R.id.etEditProfileEmail)
        val etPhone = dialogView.findViewById<EditText>(R.id.etEditProfilePhone)

        etName?.setText(currentName)
        etEmail?.setText(currentEmail)
        etPhone?.setText(currentPhone)

        dialogView.findViewById<android.view.View>(R.id.btnCancelEditProfile)?.setOnClickListener {
            dialog.dismiss()
        }

        dialogView.findViewById<android.view.View>(R.id.btnSaveEditProfile)?.setOnClickListener {
            val newName = etName?.text?.toString()?.trim().orEmpty()
            val newEmail = etEmail?.text?.toString()?.trim().orEmpty()
            val newPhone = etPhone?.text?.toString()?.trim().orEmpty()
            if (newName.isNotBlank()) {
                UserSessionManager.saveUser(
                    this,
                    name = newName,
                    email = newEmail.ifBlank { UserSessionManager.getUserEmail(this) },
                    phone = newPhone
                )
                loadUserProfile()
                dialog.dismiss()
                Toast.makeText(this, "Profil berhasil diperbarui!", Toast.LENGTH_SHORT).show()
            } else {
                Toast.makeText(this, "Nama tidak boleh kosong", Toast.LENGTH_SHORT).show()
            }
        }

        dialog.show()
    }

    private fun showDigitalMemberCardDialog() {
        val name = UserSessionManager.getUserName(this)
        val email = UserSessionManager.getUserEmail(this)
        val memberId = UserSessionManager.getMemberId(this)

        AlertDialog.Builder(this)
            .setTitle("Kartu Anggota Digital")
            .setMessage(
                """
                Nama: $name
                Surel: $email
                Nomor Anggota: $memberId
                Kategori: Anggota Pena Cendekia Mobile (HP)
                Status: Anggota Pembaca Terverifikasi
                Masa Berlaku: Aktif Selamanya
                Penerbit: PT Pena Cendekia Pustaka
                IKAPI No: 638/JTI/2023
                """.trimIndent()
            )
            .setPositiveButton("Tutup", null)
            .show()
    }

    private fun showMyReviewsDialog() {
        val options = arrayOf(
            "Tulis Ulasan untuk Buku Baru...",
            "Lihat Riwayat Ulasan di Web Pena Cendekia"
        )
        AlertDialog.Builder(this)
            .setTitle("Ulasan & Rating Buku")
            .setItems(options) { _, which ->
                if (which == 0) {
                    PageTransition.slideForward(this, Intent(this, CatalogActivity::class.java))
                } else {
                    openAuthorDashboard()
                }
            }
            .setNegativeButton("Tutup", null)
            .show()
    }

    private fun showChangePasswordDialog() {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_change_password, null)
        val dialog = AlertDialog.Builder(this)
            .setView(dialogView)
            .create()

        dialog.window?.setBackgroundDrawable(ColorDrawable(Color.TRANSPARENT))

        val etOldPass = dialogView.findViewById<EditText>(R.id.etOldPassword)
        val etNewPass = dialogView.findViewById<EditText>(R.id.etNewPassword)
        val etConfirmPass = dialogView.findViewById<EditText>(R.id.etConfirmPassword)

        dialogView.findViewById<android.view.View>(R.id.btnCancelPassword)?.setOnClickListener {
            dialog.dismiss()
        }

        dialogView.findViewById<android.view.View>(R.id.btnSavePassword)?.setOnClickListener {
            val oldP = etOldPass?.text?.toString()?.trim().orEmpty()
            val newP = etNewPass?.text?.toString()?.trim().orEmpty()
            val confP = etConfirmPass?.text?.toString()?.trim().orEmpty()

            if (oldP.isEmpty()) {
                Toast.makeText(this, "Masukkan kata sandi saat ini", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            if (newP.length < 6) {
                Toast.makeText(this, "Kata sandi baru minimal 6 karakter", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            if (newP != confP) {
                Toast.makeText(this, "Konfirmasi kata sandi baru tidak cocok", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            dialog.dismiss()
            Toast.makeText(this, "Kata sandi berhasil diperbarui!", Toast.LENGTH_SHORT).show()
        }

        dialog.show()
    }

    private fun showPrivacySecurityDialog() {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_privacy_security, null)
        val dialog = AlertDialog.Builder(this)
            .setView(dialogView)
            .create()

        dialog.window?.setBackgroundDrawable(ColorDrawable(Color.TRANSPARENT))

        dialogView.findViewById<android.view.View>(R.id.btnDismissPrivacy)?.setOnClickListener {
            dialog.dismiss()
        }

        dialog.show()
    }

    private fun showHelpCenterDialog() {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_help_center, null)
        val dialog = AlertDialog.Builder(this)
            .setView(dialogView)
            .create()

        dialog.window?.setBackgroundDrawable(ColorDrawable(Color.TRANSPARENT))

        dialogView.findViewById<android.view.View>(R.id.btnHelpEmail)?.setOnClickListener {
            dialog.dismiss()
            try {
                val intent = Intent(Intent.ACTION_SENDTO).apply {
                    data = Uri.parse("mailto:penacendekiasby@gmail.com")
                    putExtra(Intent.EXTRA_SUBJECT, "Pertanyaan Layanan Pena Cendekia")
                }
                startActivity(intent)
            } catch (e: Exception) {
                Toast.makeText(this, "Surel: penacendekiasby@gmail.com", Toast.LENGTH_LONG).show()
            }
        }

        dialogView.findViewById<android.view.View>(R.id.btnHelpWhatsApp)?.setOnClickListener {
            dialog.dismiss()
            try {
                val intent = Intent(Intent.ACTION_VIEW, Uri.parse("https://wa.me/6285785522283"))
                startActivity(intent)
            } catch (e: Exception) {
                Toast.makeText(this, "WhatsApp: 0857-8552-2283", Toast.LENGTH_LONG).show()
            }
        }

        dialogView.findViewById<android.view.View>(R.id.btnHelpFaq)?.setOnClickListener {
            dialog.dismiss()
            openContactPage()
        }

        dialogView.findViewById<android.view.View>(R.id.btnDismissHelp)?.setOnClickListener {
            dialog.dismiss()
        }

        dialog.show()
    }

    private fun showAboutPublisherDialog() {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_about_publisher, null)
        val dialog = AlertDialog.Builder(this)
            .setView(dialogView)
            .create()

        dialog.window?.setBackgroundDrawable(ColorDrawable(Color.TRANSPARENT))

        dialogView.findViewById<android.view.View>(R.id.btnDismissAbout)?.setOnClickListener {
            dialog.dismiss()
        }

        dialog.show()
    }

    private fun showLogoutConfirmDialog() {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_logout_confirm, null)
        val dialog = AlertDialog.Builder(this)
            .setView(dialogView)
            .create()

        // Background transparan agar sudut rounded MaterialCardView tampil rapi tanpa kotak abu-abu sistem
        dialog.window?.setBackgroundDrawable(android.graphics.drawable.ColorDrawable(android.graphics.Color.TRANSPARENT))

        dialogView.findViewById<android.view.View>(R.id.btnCancelLogout)?.setOnClickListener {
            dialog.dismiss()
        }

        dialogView.findViewById<android.view.View>(R.id.btnConfirmLogout)?.setOnClickListener {
            dialog.dismiss()
            UserSessionManager.clearSession(this)
            val intent = Intent(this, LoginActivity::class.java).apply {
                flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
            }
            startActivity(intent)
            finish()
        }

        dialog.show()
    }

    private fun openAuthorDashboard() {
        try {
            val authorUrl = "http://${RetrofitClient.currentHost}:${RetrofitClient.PORT}/login"
            val intent = Intent(Intent.ACTION_VIEW, Uri.parse(authorUrl))
            startActivity(intent)
        } catch (e: Exception) {
            Toast.makeText(this, "Tidak dapat membuka peramban web", Toast.LENGTH_SHORT).show()
        }
    }

    private fun openContactPage() {
        try {
            val contactUrl = "http://${RetrofitClient.currentHost}:${RetrofitClient.PORT}/contact"
            val intent = Intent(Intent.ACTION_VIEW, Uri.parse(contactUrl))
            startActivity(intent)
        } catch (e: Exception) {
            Toast.makeText(this, "Tidak dapat membuka peramban web", Toast.LENGTH_SHORT).show()
        }
    }
}
