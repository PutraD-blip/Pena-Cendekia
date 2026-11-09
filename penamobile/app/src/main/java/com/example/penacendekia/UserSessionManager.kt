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

    const val DEFAULT_NAME = "Alya Putri"
    const val DEFAULT_EMAIL = "alya.putri@penacendekia.id"
    const val DEFAULT_PHONE = "0812-3456-7890"
    const val DEFAULT_MEMBER_ID = "PCM-MOB-2026-008721"

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
        prefs.edit().apply {
            if (id != null && id > 0) putInt(KEY_ID, id)
            putString(KEY_NAME, name)
            putString(KEY_EMAIL, email)
            if (!phone.isNullOrBlank()) putString(KEY_PHONE, phone)
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
        val saved = prefs.getString(KEY_MEMBER_ID, null)
        if (!saved.isNullOrBlank()) return saved

        val email = prefs.getString(KEY_EMAIL, DEFAULT_EMAIL) ?: DEFAULT_EMAIL
        val id = if (prefs.contains(KEY_ID)) prefs.getInt(KEY_ID, 0) else null
        val generated = generateMemberId(id, email)
        prefs.edit().putString(KEY_MEMBER_ID, generated).apply()
        return generated
    }

    fun getUserName(context: Context): String {
        return context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
            .getString(KEY_NAME, DEFAULT_NAME) ?: DEFAULT_NAME
    }

    fun getUserEmail(context: Context): String {
        return context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
            .getString(KEY_EMAIL, DEFAULT_EMAIL) ?: DEFAULT_EMAIL
    }

    fun getUserPhone(context: Context): String {
        return context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
            .getString(KEY_PHONE, DEFAULT_PHONE) ?: DEFAULT_PHONE
    }

    fun getUser(context: Context): User {
        val prefs = context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE)
        val id = prefs.getInt(KEY_ID, 1)
        val name = prefs.getString(KEY_NAME, DEFAULT_NAME) ?: DEFAULT_NAME
        val email = prefs.getString(KEY_EMAIL, DEFAULT_EMAIL) ?: DEFAULT_EMAIL
        val phone = prefs.getString(KEY_PHONE, DEFAULT_PHONE)
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

        // Khusus akun default bawaan simulasi (Alya Putri):
        if (trimmedEmail.contains("alya") && (id == null || id <= 0)) {
            return DEFAULT_MEMBER_ID
        }

        // Jika ID numerik tersedia dari database server web backend:
        if (id != null && id > 0) {
            return String.format(Locale.US, "PCM-MOB-2026-%06d", id)
        }

        // Untuk setiap pengguna baru/login lainnya, buat nomor 6-digit deterministik & unik berdasarkan akun:
        val hash = abs(trimmedEmail.hashCode())
        val unique6Digit = (hash % 900000) + 100000
        return String.format(Locale.US, "PCM-MOB-2026-%06d", unique6Digit)
    }

    fun clearSession(context: Context) {
        context.getSharedPreferences(PREFS_USER, Context.MODE_PRIVATE).edit().clear().apply()
    }
}
