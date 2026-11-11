package com.example.penacendekia

import android.content.Context
import com.example.penacendekia.data.model.User
import java.util.Locale
import kotlin.math.abs

object UserSessionManager {
    private const val PREFS_USER = "user_prefs"
    const val KEY_ID = "user_id"
    const val KEY_NAME = "user_name"
    const val KEY_EMAIL = "user_email"
    const val KEY_PHONE = "user_phone"
    const val KEY_ROLE = "user_role"
    const val KEY_MEMBER_ID = "user_member_id"
    const val KEY_BIO = "user_bio"

    const val DEFAULT_NAME = "Pembaca"
    const val DEFAULT_EMAIL = ""
    const val DEFAULT_PHONE = ""
    const val DEFAULT_MEMBER_ID = ""

    // Akun demo bawaan simulasi (Alya Putri)
    const val ALYA_NAME = "Alya Putri"
    const val ALYA_EMAIL = "alya.cendekia@gmail.com"
    const val ALYA_PHONE = "0812-3456-7890"
    const val ALYA_MEMBER_ID = "PCM-MOB-2026-008721"

    /**
     * Menyimpan sesi login pengguna lengkap dengan ID Anggota Mobile unik (PCM-MOB-2026-XXXXXX)
     */
    fun saveUser(
        context: Context,
        id: Int? = null,
        name: String,
        email: String,
        phone: String? = null,
        role: String? = "reader"
    ): String {
        val prefs = context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
        val memberId = generateMemberId(id, email)
        val isAlya = email.trim().lowercase().contains("alya")
        val effectivePhone = if (!phone.isNullOrBlank()) {
            phone
        } else if (isAlya) {
            ALYA_PHONE
        } else {
            null
        }

        prefs.edit().apply {
            if (id != null && id > 0) putInt(KEY_ID, id)
            putString(KEY_NAME, if (isAlya && name.isBlank()) ALYA_NAME else name)
            putString(KEY_EMAIL, email)
            if (!effectivePhone.isNullOrBlank()) {
                putString(KEY_PHONE, effectivePhone)
            } else {
                remove(KEY_PHONE)
            }
            if (!role.isNullOrBlank()) putString(KEY_ROLE, role)
            putString(KEY_MEMBER_ID, memberId)
            apply()
        }
        return memberId
    }

    fun saveUser(context: Context, user: User, phone: String? = null): String {
        return saveUser(
            context = context,
            id = user.id,
            name = user.name,
            email = user.email,
            phone = phone ?: user.phone,
            role = user.role
        )
    }

    /**
     * Mengambil ID Anggota Mobile pengguna saat ini
     */
    fun getMemberId(context: Context): String {
        val prefs = context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
        val email = prefs.getString(KEY_EMAIL, "") ?: ""
        if (email.trim().lowercase().contains("alya")) {
            return ALYA_MEMBER_ID
        }

        val saved = prefs.getString(KEY_MEMBER_ID, null)
        if (!saved.isNullOrBlank()) return saved

        val id = if (prefs.contains(KEY_ID)) prefs.getInt(KEY_ID, 0) else null
        val generated = generateMemberId(id, email)
        prefs.edit().putString(KEY_MEMBER_ID, generated).apply()
        return generated
    }

    fun getUserName(context: Context): String {
        val prefs = context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
        val email = prefs.getString(KEY_EMAIL, "") ?: ""
        val saved = prefs.getString(KEY_NAME, "") ?: ""
        if (saved.isNotBlank()) return saved
        return if (email.trim().lowercase().contains("alya")) ALYA_NAME else DEFAULT_NAME
    }

    fun getUserEmail(context: Context): String {
        return context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
            .getString(KEY_EMAIL, "") ?: ""
    }

    fun getUserPhone(context: Context): String {
        val prefs = context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
        val email = prefs.getString(KEY_EMAIL, "") ?: ""
        val saved = prefs.getString(KEY_PHONE, "") ?: ""
        if (saved.isNotBlank()) return saved
        return if (email.trim().lowercase().contains("alya")) ALYA_PHONE else ""
    }

    fun getUser(context: Context): User {
        val prefs = context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
        val id = prefs.getInt(KEY_ID, 0)
        val email = prefs.getString(KEY_EMAIL, "") ?: ""
        val isAlya = email.trim().lowercase().contains("alya")
        val name = prefs.getString(KEY_NAME, "")?.takeIf { it.isNotBlank() } ?: (if (isAlya) ALYA_NAME else DEFAULT_NAME)
        val phone = prefs.getString(KEY_PHONE, null)?.takeIf { it.isNotBlank() } ?: (if (isAlya) ALYA_PHONE else null)
        val role = prefs.getString(KEY_ROLE, "reader") ?: "reader"
        return User(id = id, name = name, email = email, role = role, phone = phone)
    }

    /**
     * Menghasilkan ID unik khusus pengguna aplikasi Mobile (HP / Smartphone):
     * Format: PCM-MOB-2026-XXXXXX
     * Keterangan:
     * - PCM : Pena Cendekia Mobile
     * - MOB : Penanda perangkat Mobile (HP)
     * - 2026: Tahun aktif keanggotaan
     * - XXXXXX: 6-digit nomor identitas unik pengguna
     *
     * Setiap pengguna baru akan mendapatkan nomor ID yang unik dan berbeda satu sama lain!
     */
    fun generateMemberId(id: Int?, email: String): String {
        val trimmedEmail = email.trim().lowercase()

        // Khusus akun demo simulasi bawaan (Alya Putri):
        if (trimmedEmail.contains("alya")) {
            return ALYA_MEMBER_ID
        }

        // Jika ID numerik tersedia dari database server web backend:
        if (id != null && id > 0) {
            return String.format(Locale.US, "PCM-MOB-2026-%06d", id)
        }

        if (trimmedEmail.isEmpty()) {
            return "PCM-MOB-2026-000000"
        }

        // Untuk setiap pengguna baru/login lainnya, buat nomor 6-digit deterministik & unik berdasarkan akun:
        val hash = abs(trimmedEmail.hashCode())
        val unique6Digit = (hash % 900000) + 100000
        return String.format(Locale.US, "PCM-MOB-2026-%06d", unique6Digit)
    }

    fun isLoggedIn(context: Context): Boolean {
        val prefs = context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
        val email = prefs.getString(KEY_EMAIL, null)
        return !email.isNullOrBlank()
    }

    fun clearSession(context: Context) {
        context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE).edit().clear().apply()
    }
}
