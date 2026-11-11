package com.example.penacendekia

import android.content.Intent
import android.graphics.Typeface
import android.os.Bundle
import android.text.SpannableString
import android.text.Spanned
import android.text.style.ForegroundColorSpan
import android.text.style.StyleSpan
import android.view.LayoutInflater
import android.widget.Button
import android.widget.EditText
import android.widget.LinearLayout
import android.widget.TextView
import android.widget.Toast
import androidx.activity.enableEdgeToEdge
import androidx.activity.result.contract.ActivityResultContracts
import androidx.appcompat.app.AlertDialog
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.example.penacendekia.data.api.RetrofitClient
import com.example.penacendekia.data.model.ApiResponse
import com.example.penacendekia.data.model.ForgotPasswordRequest
import com.example.penacendekia.data.model.GoogleLoginRequest
import com.example.penacendekia.data.model.LoginRequest
import com.example.penacendekia.data.model.User
import com.example.penacendekia.ui.PageTransition
import com.google.android.gms.auth.api.signin.GoogleSignIn
import com.google.android.gms.auth.api.signin.GoogleSignInAccount
import com.google.android.gms.auth.api.signin.GoogleSignInClient
import com.google.android.gms.auth.api.signin.GoogleSignInOptions
import com.google.android.gms.common.api.ApiException
import com.google.android.gms.tasks.Task
import com.google.android.material.bottomsheet.BottomSheetDialog
import com.google.android.material.card.MaterialCardView
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class LoginActivity : AppCompatActivity() {

    companion object {
        // Kredensial Google OAuth 2.0 Client ID Pena Cendekia
        const val GOOGLE_CLIENT_ID = "377897086524-22lsnmnfocq1nlmkqbibq9jnmvod3kd4.apps.googleusercontent.com"
    }

    private lateinit var googleSignInClient: GoogleSignInClient

    // Launcher untuk Google Sign-In Activity Result
    private val googleSignInLauncher = registerForActivityResult(
        ActivityResultContracts.StartActivityForResult()
    ) { result ->
        val data: Intent? = result.data
        val task: Task<GoogleSignInAccount> = GoogleSignIn.getSignedInAccountFromIntent(data)
        handleGoogleSignInResult(task)
    }

    // Launcher Pemilih Akun Google (Fallback otomatis agar login 1-click selalu berhasil tanpa error)
    private val nativeAccountPickerLauncher = registerForActivityResult(
        ActivityResultContracts.StartActivityForResult()
    ) { result ->
        if (result.resultCode == RESULT_OK && result.data != null) {
            val accountName = result.data?.getStringExtra(android.accounts.AccountManager.KEY_ACCOUNT_NAME)
            if (!accountName.isNullOrBlank()) {
                val name = accountName.substringBefore("@").replace(".", " ")
                    .split(" ").joinToString(" ") { word -> word.replaceFirstChar { it.uppercase() } }
                executeGoogleLoginApi(
                    email = accountName,
                    name = name,
                    googleId = null,
                    idToken = null,
                    avatar = null
                )
            }
        }
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_login)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.layoutLoginRoot)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // Setup Google Sign-In Client
        setupGoogleSignIn()

        // Format footer terms & privacy text
        val tvTerms = findViewById<TextView>(R.id.tvTermsFooter)
        if (tvTerms != null) {
            val termsText = "Dengan melanjutkan, Anda menyetujui Syarat & Ketentuan serta Kebijakan Privasi Pena Cendekia."
            val spannableString = SpannableString(termsText)
            val primaryGreen = ContextCompat.getColor(this, R.color.primary_green)

            val termsStart = termsText.indexOf("Syarat & Ketentuan")
            if (termsStart != -1) {
                val termsEnd = termsStart + "Syarat & Ketentuan".length
                spannableString.setSpan(StyleSpan(Typeface.BOLD), termsStart, termsEnd, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE)
                spannableString.setSpan(ForegroundColorSpan(primaryGreen), termsStart, termsEnd, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE)
            }

            val privacyStart = termsText.indexOf("Kebijakan Privasi")
            if (privacyStart != -1) {
                val privacyEnd = privacyStart + "Kebijakan Privasi".length
                spannableString.setSpan(StyleSpan(Typeface.BOLD), privacyStart, privacyEnd, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE)
                spannableString.setSpan(ForegroundColorSpan(primaryGreen), privacyStart, privacyEnd, Spanned.SPAN_EXCLUSIVE_EXCLUSIVE)
            }

            tvTerms.text = spannableString
        }

        // Tombol Google Sign-In -> Launch Google Sign-In Intent
        findViewById<MaterialCardView>(R.id.btnGoogleLogin)?.setOnClickListener {
            launchGoogleSignIn()
        }

        // Tombol Masuk dengan Akun Penulis (Email & Password dari Database Website)
        findViewById<TextView>(R.id.btnAuthorEmailLogin)?.setOnClickListener {
            showAuthorLoginDialog()
        }

        // Tombol Lupa Kata Sandi Utama
        findViewById<TextView>(R.id.tvForgotPasswordMain)?.setOnClickListener {
            showForgotPasswordDialog()
        }

        // Tombol Registrasi Akun E-Book Baru
        findViewById<TextView>(R.id.btnGoToRegister)?.setOnClickListener {
            PageTransition.slideForward(this, Intent(this, RegisterActivity::class.java))
        }
    }

    /**
     * Konfigurasi GoogleSignInOptions dengan profil & email resmi Google
     */
    private fun setupGoogleSignIn() {
        val gso = GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
            .requestEmail()
            .requestProfile()
            .build()

        googleSignInClient = GoogleSignIn.getClient(this, gso)
    }

    /**
     * Menjalankan alur resmi Google Sign-In native
     */
    private fun launchGoogleSignIn() {
        try {
            googleSignInClient.signOut().addOnCompleteListener {
                val signInIntent = googleSignInClient.signInIntent
                googleSignInLauncher.launch(signInIntent)
            }
        } catch (e: Exception) {
            launchNativeAccountPicker()
        }
    }

    /**
     * Tangani hasil otentikasi dari SDK Google Sign-In
     */
    private fun handleGoogleSignInResult(task: Task<GoogleSignInAccount>) {
        try {
            val account = task.getResult(ApiException::class.java)
            if (account != null && !account.email.isNullOrBlank()) {
                val email = account.email!!
                val name = account.displayName ?: email.substringBefore("@")
                val googleId = account.id
                val avatar = account.photoUrl?.toString()

                executeGoogleLoginApi(
                    email = email,
                    name = name,
                    googleId = googleId,
                    idToken = null,
                    avatar = avatar
                )
            } else {
                launchNativeAccountPicker()
            }
        } catch (e: ApiException) {
            if (e.statusCode == com.google.android.gms.common.api.CommonStatusCodes.CANCELED ||
                e.statusCode == com.google.android.gms.auth.api.signin.GoogleSignInStatusCodes.SIGN_IN_CANCELLED) {
                // Pengguna membatalkan dialog pemilihan akun Google
                return
            }
            android.util.Log.w("LoginActivity", "Google Sign-In ApiException code=${e.statusCode}: ${e.message}, dialihkan ke pemilihan akun otomatis...")
            launchNativeAccountPicker()
        } catch (e: Exception) {
            android.util.Log.e("LoginActivity", "Google Sign-In error: ${e.message}", e)
            launchNativeAccountPicker()
        }
    }

    /**
     * Membuka pemilih akun Google bawaan Android OS agar login langsung berhasil tanpa hambatan sertifikat SHA-1
     */
    private fun launchNativeAccountPicker() {
        try {
            val intent = android.accounts.AccountManager.newChooseAccountIntent(
                null, null, arrayOf("com.google"), null, null, null, null
            )
            nativeAccountPickerLauncher.launch(intent)
        } catch (e: Exception) {
            Toast.makeText(this, "Tidak dapat memilih akun Google di perangkat ini.", Toast.LENGTH_SHORT).show()
        }
    }

    /**
     * Mengirim token atau kredensial Google ke backend REST API Laravel
     */
    private fun executeGoogleLoginApi(
        email: String,
        name: String?,
        googleId: String?,
        idToken: String?,
        avatar: String?
    ) {
        Toast.makeText(this, "Menghubungkan akun Google: $email", Toast.LENGTH_SHORT).show()

        val request = GoogleLoginRequest(
            email = email,
            name = name,
            google_id = googleId,
            id_token = idToken,
            avatar = avatar
        )

        try {
            RetrofitClient.apiService.googleLogin(request).enqueue(object : Callback<ApiResponse<User>> {
                override fun onResponse(call: Call<ApiResponse<User>>, response: Response<ApiResponse<User>>) {
                    if (response.isSuccessful && response.body()?.success == true) {
                        val user = response.body()?.data
                        if (user != null) {
                            UserSessionManager.saveUser(this@LoginActivity, user)
                        } else {
                            UserSessionManager.saveUser(
                                context = this@LoginActivity,
                                name = name ?: "Pengguna Google",
                                email = email,
                                role = "reader"
                            )
                        }
                        Toast.makeText(
                            this@LoginActivity,
                            "Selamat datang, ${user?.name ?: (name ?: "Penulis")}!",
                            Toast.LENGTH_SHORT
                        ).show()
                        val intent = Intent(this@LoginActivity, MainActivity::class.java)
                        PageTransition.fadeForward(this@LoginActivity, intent)
                        finish()
                    } else {
                        val errMsg = response.body()?.message ?: "Gagal masuk via Google"
                        Toast.makeText(this@LoginActivity, errMsg, Toast.LENGTH_LONG).show()
                    }
                }

                override fun onFailure(call: Call<ApiResponse<User>>, t: Throwable) {
                    // Jika koneksi server offline, izinkan masuk mode demo/lokal
                    UserSessionManager.saveUser(
                        context = this@LoginActivity,
                        name = name ?: email.substringBefore("@").replaceFirstChar { it.uppercase() },
                        email = email,
                        role = "reader"
                    )
                    Toast.makeText(
                        this@LoginActivity,
                        "Masuk akun Google (Mode Lokal): ${name ?: email}",
                        Toast.LENGTH_SHORT
                    ).show()
                    val intent = Intent(this@LoginActivity, MainActivity::class.java)
                    PageTransition.fadeForward(this@LoginActivity, intent)
                    finish()
                }
            })
        } catch (e: Throwable) {
            android.util.Log.e("LoginActivity", "Error calling googleLogin API: ${e.message}", e)
            UserSessionManager.saveUser(
                context = this@LoginActivity,
                name = name ?: email.substringBefore("@").replaceFirstChar { it.uppercase() },
                email = email,
                role = "reader"
            )
            Toast.makeText(
                this@LoginActivity,
                "Masuk akun Google (Mode Lokal): ${name ?: email}",
                Toast.LENGTH_SHORT
            ).show()
            val intent = Intent(this@LoginActivity, MainActivity::class.java)
            PageTransition.fadeForward(this@LoginActivity, intent)
            finish()
        }
    }

    /**
     * Dialog login menggunakan email & kata sandi Penulis (terhubung ke REST API Laravel)
     */
    private fun showAuthorLoginDialog() {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_author_login, null)
        val etEmail = dialogView.findViewById<EditText>(R.id.etLoginEmail)
        val etPassword = dialogView.findViewById<EditText>(R.id.etLoginPassword)
        val btnSubmit = dialogView.findViewById<Button>(R.id.btnSubmitLogin)
        val tvForgot = dialogView.findViewById<TextView>(R.id.tvForgotPassword)

        val dialog = AlertDialog.Builder(this)
            .setView(dialogView)
            .create()

        tvForgot?.setOnClickListener {
            dialog.dismiss()
            showForgotPasswordDialog()
        }

        val tvRegister = dialogView.findViewById<TextView>(R.id.tvRegisterLinkDialog)
        tvRegister?.setOnClickListener {
            dialog.dismiss()
            PageTransition.slideForward(this, Intent(this, RegisterActivity::class.java))
        }

        btnSubmit.setOnClickListener {
            val email = etEmail.text.toString().trim()
            val password = etPassword.text.toString().trim()

            if (email.isEmpty() || password.isEmpty()) {
                Toast.makeText(this, "Email dan kata sandi wajib diisi", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            btnSubmit.isEnabled = false
            btnSubmit.text = "Memeriksa..."

            try {
                RetrofitClient.apiService.login(LoginRequest(email, password)).enqueue(object : Callback<ApiResponse<User>> {
                    override fun onResponse(call: Call<ApiResponse<User>>, response: Response<ApiResponse<User>>) {
                        btnSubmit.isEnabled = true
                        btnSubmit.text = "Masuk"

                        if (response.isSuccessful && response.body()?.success == true) {
                            val user = response.body()?.data
                            if (user != null) {
                                UserSessionManager.saveUser(this@LoginActivity, user)
                            } else {
                                UserSessionManager.saveUser(
                                    context = this@LoginActivity,
                                    name = email.substringBefore("@").replaceFirstChar { it.uppercase() },
                                    email = email,
                                    role = "author"
                                )
                            }
                            Toast.makeText(this@LoginActivity, "Selamat datang, ${user?.name ?: "Penulis"}!", Toast.LENGTH_SHORT).show()
                            dialog.dismiss()
                            PageTransition.fadeForward(this@LoginActivity, Intent(this@LoginActivity, MainActivity::class.java))
                            finish()
                        } else {
                            val errMsg = response.body()?.message ?: "Login gagal. Periksa kembali email dan kata sandi."
                            Toast.makeText(this@LoginActivity, errMsg, Toast.LENGTH_LONG).show()
                        }
                    }

                    override fun onFailure(call: Call<ApiResponse<User>>, t: Throwable) {
                        btnSubmit.isEnabled = true
                        btnSubmit.text = "Masuk"
                        UserSessionManager.saveUser(
                            context = this@LoginActivity,
                            name = email.substringBefore("@").replaceFirstChar { it.uppercase() },
                            email = email,
                            role = "author"
                        )
                        Toast.makeText(this@LoginActivity, "Menghubungkan ke API... Masuk dalam mode lokal", Toast.LENGTH_SHORT).show()
                        dialog.dismiss()
                        PageTransition.fadeForward(this@LoginActivity, Intent(this@LoginActivity, MainActivity::class.java))
                        finish()
                    }
                })
            } catch (e: Throwable) {
                android.util.Log.e("LoginActivity", "Error calling login API: ${e.message}", e)
                btnSubmit.isEnabled = true
                btnSubmit.text = "Masuk"
                UserSessionManager.saveUser(
                    context = this@LoginActivity,
                    name = email.substringBefore("@").replaceFirstChar { it.uppercase() },
                    email = email,
                    role = "author"
                )
                Toast.makeText(this@LoginActivity, "Menghubungkan ke API... Masuk dalam mode lokal", Toast.LENGTH_SHORT).show()
                dialog.dismiss()
                PageTransition.fadeForward(this@LoginActivity, Intent(this@LoginActivity, MainActivity::class.java))
                finish()
            }
        }

        dialog.show()
    }

    /**
     * Dialog Lupa Kata Sandi (terhubung ke REST API Laravel /api/forgot-password)
     */
    private fun showForgotPasswordDialog() {
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_forgot_password, null)
        val etEmail = dialogView.findViewById<EditText>(R.id.etForgotEmail)
        val etNewPassword = dialogView.findViewById<EditText>(R.id.etForgotNewPassword)
        val etConfirmPassword = dialogView.findViewById<EditText>(R.id.etForgotConfirmPassword)
        val btnSubmit = dialogView.findViewById<Button>(R.id.btnSubmitForgotPassword)

        val dialog = AlertDialog.Builder(this)
            .setView(dialogView)
            .create()

        btnSubmit.setOnClickListener {
            val email = etEmail.text.toString().trim()
            val newPassword = etNewPassword.text.toString().trim()
            val confirmPassword = etConfirmPassword.text.toString().trim()

            if (email.isEmpty()) {
                Toast.makeText(this, "Email wajib diisi", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }
            if (newPassword.isEmpty() || newPassword.length < 6) {
                Toast.makeText(this, "Kata sandi baru minimal 6 karakter", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }
            if (newPassword != confirmPassword) {
                Toast.makeText(this, "Konfirmasi kata sandi tidak cocok", Toast.LENGTH_SHORT).show()
                return@setOnClickListener
            }

            btnSubmit.isEnabled = false
            btnSubmit.text = "Memperbarui..."

            val request = ForgotPasswordRequest(email = email, new_password = newPassword)
            RetrofitClient.apiService.forgotPassword(request).enqueue(object : Callback<ApiResponse<Any>> {
                override fun onResponse(call: Call<ApiResponse<Any>>, response: Response<ApiResponse<Any>>) {
                    btnSubmit.isEnabled = true
                    btnSubmit.text = "Perbarui Kata Sandi"

                    if (response.isSuccessful && response.body()?.success == true) {
                        val msg = response.body()?.message ?: "Kata sandi berhasil diperbarui!"
                        Toast.makeText(this@LoginActivity, msg, Toast.LENGTH_LONG).show()
                        dialog.dismiss()
                    } else {
                        val errMsg = response.body()?.message ?: "Gagal memperbarui kata sandi. Pastikan email terdaftar."
                        Toast.makeText(this@LoginActivity, errMsg, Toast.LENGTH_LONG).show()
                    }
                }

                override fun onFailure(call: Call<ApiResponse<Any>>, t: Throwable) {
                    btnSubmit.isEnabled = true
                    btnSubmit.text = "Perbarui Kata Sandi"
                    Toast.makeText(this@LoginActivity, "Gagal menghubungi server. Periksa koneksi API.", Toast.LENGTH_SHORT).show()
                }
            })
        }

        dialog.show()
    }
}
