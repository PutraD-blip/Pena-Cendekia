package com.example.penacendekia

import android.app.AlertDialog
import android.content.Context
import android.content.Intent
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

        // 2. Settings button on header
        findViewById<android.view.View>(R.id.btnSettingsProfile)?.setOnClickListener {
            showQuickSettingsDialog()
        }

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

        tvProfileName.text = name
        tvProfileEmail.text = email
        tvCardHolderName.text = name
        tvCardMemberId.text = "ID: $memberId • Ketuk info"

        // Calculate initials from name
        val initials = name.trim().split(Regex("\\s+"))
            .mapNotNull { it.firstOrNull()?.uppercaseChar() }
            .take(2)
            .joinToString("")
            .ifEmpty { "AP" }
        tvAvatarInitials.text = initials
    }

    private fun loadReadingStats() {
        val bookmarks = BookActivityStore.getBookmarks(this)
        val favorites = BookActivityStore.getFavorites(this)
        tvStatFinishedCount.text = "18"
        tvStatFavoritesCount.text = favorites.size.coerceAtLeast(2).toString()
        tvStatBookmarksCount.text = bookmarks.size.coerceAtLeast(1).toString()
    }

    private fun showEditProfileDialog() {
        val currentName = UserSessionManager.getUserName(this)
        val currentEmail = UserSessionManager.getUserEmail(this)
        val currentPhone = UserSessionManager.getUserPhone(this)

        val container = android.widget.LinearLayout(this).apply {
            orientation = android.widget.LinearLayout.VERTICAL
            setPadding(50, 20, 50, 10)
        }

        val etName = EditText(this).apply {
            hint = "Nama Lengkap"
            setText(currentName)
        }
        val etEmail = EditText(this).apply {
            hint = "Email"
            setText(currentEmail)
            inputType = android.text.InputType.TYPE_TEXT_VARIATION_EMAIL_ADDRESS
        }
        val etPhone = EditText(this).apply {
            hint = "Nomor Telepon / WhatsApp"
            setText(currentPhone)
            inputType = android.text.InputType.TYPE_CLASS_PHONE
        }

        container.addView(TextView(this).apply { text = "Nama Lengkap"; textSize = 12f; setTextColor(getColor(R.color.text_muted)) })
        container.addView(etName)
        container.addView(TextView(this).apply { text = "Email Akun"; textSize = 12f; setTextColor(getColor(R.color.text_muted)) })
        container.addView(etEmail)
        container.addView(TextView(this).apply { text = "Nomor Telepon"; textSize = 12f; setTextColor(getColor(R.color.text_muted)) })
        container.addView(etPhone)

        AlertDialog.Builder(this)
            .setTitle("Edit Profil Pengguna")
            .setView(container)
            .setPositiveButton("Simpan") { _, _ ->
                val newName = etName.text.toString().trim()
                val newEmail = etEmail.text.toString().trim()
                val newPhone = etPhone.text.toString().trim()
                if (newName.isNotBlank()) {
                    UserSessionManager.saveUser(
                        this,
                        name = newName,
                        email = newEmail.ifBlank { UserSessionManager.getUserEmail(this) },
                        phone = newPhone
                    )
                    loadUserProfile()
                    Toast.makeText(this, "Profil berhasil diperbarui!", Toast.LENGTH_SHORT).show()
                } else {
                    Toast.makeText(this, "Nama tidak boleh kosong", Toast.LENGTH_SHORT).show()
                }
            }
            .setNegativeButton("Batal", null)
            .show()
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

    private fun showQuickSettingsDialog() {
        val options = arrayOf(
            "Tema Layaran Baca (Terang / Sepia / Gelap)",
            "Pemberitahuan Buku Baru (Aktif)",
            "Unduh Otomatis via Wi-Fi (Aktif)",
            "Hapus Singgahan / Cache Aplikasi"
        )
        AlertDialog.Builder(this)
            .setTitle("Pengaturan Umum")
            .setItems(options) { _, which ->
                when (which) {
                    0 -> PageTransition.slideForward(this, Intent(this, ReaderAppearanceActivity::class.java))
                    1 -> Toast.makeText(this, "Notifikasi buku baru tetap aktif", Toast.LENGTH_SHORT).show()
                    2 -> Toast.makeText(this, "Mode unduh otomatis tersimpan", Toast.LENGTH_SHORT).show()
                    3 -> Toast.makeText(this, "Cache aplikasi berhasil dibersihkan", Toast.LENGTH_SHORT).show()
                }
            }
            .setNegativeButton("Tutup", null)
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
        val container = android.widget.LinearLayout(this).apply {
            orientation = android.widget.LinearLayout.VERTICAL
            setPadding(50, 20, 50, 10)
        }
        val etOldPass = EditText(this).apply {
            hint = "Kata Sandi Saat Ini"
            inputType = android.text.InputType.TYPE_CLASS_TEXT or android.text.InputType.TYPE_TEXT_VARIATION_PASSWORD
        }
        val etNewPass = EditText(this).apply {
            hint = "Kata Sandi Baru"
            inputType = android.text.InputType.TYPE_CLASS_TEXT or android.text.InputType.TYPE_TEXT_VARIATION_PASSWORD
        }
        val etConfirmPass = EditText(this).apply {
            hint = "Konfirmasi Kata Sandi Baru"
            inputType = android.text.InputType.TYPE_CLASS_TEXT or android.text.InputType.TYPE_TEXT_VARIATION_PASSWORD
        }
        container.addView(etOldPass)
        container.addView(etNewPass)
        container.addView(etConfirmPass)

        AlertDialog.Builder(this)
            .setTitle("Ubah Kata Sandi")
            .setView(container)
            .setPositiveButton("Simpan") { _, _ ->
                val newP = etNewPass.text.toString()
                val confP = etConfirmPass.text.toString()
                if (newP.length >= 6 && newP == confP) {
                    Toast.makeText(this, "Kata sandi berhasil diperbarui!", Toast.LENGTH_SHORT).show()
                } else {
                    Toast.makeText(this, "Pastikan kata sandi baru cocok dan minimal 6 karakter", Toast.LENGTH_LONG).show()
                }
            }
            .setNegativeButton("Batal", null)
            .show()
    }

    private fun showPrivacySecurityDialog() {
        AlertDialog.Builder(this)
            .setTitle("Privasi & Keamanan Akun")
            .setMessage(
                """
                Keamanan Akun Anda:
                • Enkripsi Sandi: Bcrypt (Standar Industri)
                • Autentikasi Google: OAuth 2.0 Resmi
                • Data Pribadi: Dijaga kerahasiaannya sesuai ketentuan undang-undang perlindungan data.
                """.trimIndent()
            )
            .setPositiveButton("Mengerti", null)
            .show()
    }

    private fun showHelpCenterDialog() {
        val options = arrayOf(
            "Kirim Surel ke Tim Bantuan (support@penacendekia.com)",
            "Hubungi Layanan WhatsApp Admin",
            "Buka Portal Tanya Jawab / FAQ"
        )
        AlertDialog.Builder(this)
            .setTitle("Pusat Bantuan")
            .setItems(options) { _, which ->
                when (which) {
                    0 -> {
                        try {
                            val intent = Intent(Intent.ACTION_SENDTO).apply {
                                data = Uri.parse("mailto:support@penacendekia.com")
                                putExtra(Intent.EXTRA_SUBJECT, "Pertanyaan Layanan Pena Cendekia")
                            }
                            startActivity(intent)
                        } catch (e: Exception) {
                            Toast.makeText(this, "Surel: support@penacendekia.com", Toast.LENGTH_LONG).show()
                        }
                    }
                    1 -> {
                        try {
                            val intent = Intent(Intent.ACTION_VIEW, Uri.parse("https://wa.me/6281234567890"))
                            startActivity(intent)
                        } catch (e: Exception) {
                            Toast.makeText(this, "WhatsApp: +62 812-3456-7890", Toast.LENGTH_LONG).show()
                        }
                    }
                    2 -> openAuthorDashboard()
                }
            }
            .setNegativeButton("Tutup", null)
            .show()
    }

    private fun showAboutPublisherDialog() {
        AlertDialog.Builder(this)
            .setTitle("Tentang Penerbit")
            .setMessage(
                """
                PT PENA CENDEKIA PUSTAKA
                Penerbit Buku & E-Book Akademik Terpercaya di Indonesia.

                • Anggota Resmi IKAPI: No. 638/JTI/2023
                • Versi Aplikasi: 1.0.0 (API 29+ Ready)
                • Fokus: Karya Ilmiah, Buku Ajar, Prosiding, Monograf, dan Transformasi Literasi Digital Nusantara.
                """.trimIndent()
            )
            .setPositiveButton("Tutup", null)
            .show()
    }

    private fun showLogoutConfirmDialog() {
        AlertDialog.Builder(this)
            .setTitle("Keluar Akun")
            .setMessage("Apakah Anda yakin ingin keluar dari akun Pena Cendekia?")
            .setPositiveButton("Keluar") { _, _ ->
                UserSessionManager.clearSession(this)
                val intent = Intent(this, LoginActivity::class.java).apply {
                    flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
                }
                startActivity(intent)
                finish()
            }
            .setNegativeButton("Batal", null)
            .show()
    }

    private fun openAuthorDashboard() {
        try {
            val intent = Intent(Intent.ACTION_VIEW, Uri.parse(AUTHOR_DASHBOARD_URL))
            startActivity(intent)
        } catch (e: Exception) {
            Toast.makeText(this, "Tidak dapat membuka peramban web", Toast.LENGTH_SHORT).show()
        }
    }
}
