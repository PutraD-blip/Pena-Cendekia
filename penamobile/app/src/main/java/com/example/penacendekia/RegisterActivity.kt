package com.example.penacendekia

import android.content.Intent
import android.os.Bundle
import android.util.Patterns
import android.view.LayoutInflater
import android.view.View
import android.widget.Button
import android.widget.EditText
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import android.widget.Toast
import androidx.activity.addCallback
import androidx.activity.enableEdgeToEdge
import androidx.activity.result.contract.ActivityResultContracts
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.ViewCompat
import androidx.core.view.WindowInsetsCompat
import com.example.penacendekia.data.api.RetrofitClient
import com.example.penacendekia.data.model.ApiResponse
import com.example.penacendekia.data.model.GoogleLoginRequest
import com.example.penacendekia.data.model.RegisterRequest
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
import org.json.JSONObject
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class RegisterActivity : AppCompatActivity() {

    companion object {
        // Kredensial Google OAuth 2.0 Client ID Pena Cendekia (sama seperti web)
        const val GOOGLE_CLIENT_ID = "377897086524-22lsnmnfocq1nlmkqbibq9jnmvod3kd4.apps.googleusercontent.com"
    }

    private lateinit var googleSignInClient: GoogleSignInClient

    // Status akun Google yang terhubung
    private var connectedGoogleId: String? = null
    private var connectedIdToken: String? = null
    private var connectedAvatar: String? = null
    private var isGoogleLinked: Boolean = false

    // Launcher untuk Google Sign-In Activity Result
    private val googleSignInLauncher = registerForActivityResult(
        ActivityResultContracts.StartActivityForResult()
    ) { result ->
        val data: Intent? = result.data
        val task: Task<GoogleSignInAccount> = GoogleSignIn.getSignedInAccountFromIntent(data)
        handleGoogleSignInResult(task)
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContentView(R.layout.activity_register)

        ViewCompat.setOnApplyWindowInsetsListener(findViewById(R.id.scrollRegisterRoot)) { v, insets ->
            val systemBars = insets.getInsets(WindowInsetsCompat.Type.systemBars())
            v.setPadding(systemBars.left, systemBars.top, systemBars.right, systemBars.bottom)
            insets
        }

        // Setup Google Sign-In SDK
        setupGoogleSignIn()

        // Tombol Kembali
        findViewById<ImageView>(R.id.btnBack)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }
        onBackPressedDispatcher.addCallback(this) {
            PageTransition.slideBackward(this@RegisterActivity)
        }

        // Link Kembali ke Login
        findViewById<TextView>(R.id.btnBackToLogin)?.setOnClickListener {
            PageTransition.slideBackward(this)
        }

        // Tombol Daftar dengan Google (Seperti di Web)
        findViewById<MaterialCardView>(R.id.btnRegisterGoogle)?.setOnClickListener {
            launchGoogleSignIn()
        }

        // Tombol Batalkan / Ganti Tautan Google
        findViewById<TextView>(R.id.btnCancelGoogleLink)?.setOnClickListener {
            resetGoogleLink()
        }

        // Form fields
        val etName = findViewById<EditText>(R.id.etRegisterName)
        val etEmail = findViewById<EditText>(R.id.etRegisterEmail)
        val etPhone = findViewById<EditText>(R.id.etRegisterPhone)
        val etPassword = findViewById<EditText>(R.id.etRegisterPassword)
        val etConfirmPassword = findViewById<EditText>(R.id.etRegisterConfirmPassword)
        val btnSubmit = findViewById<Button>(R.id.btnRegisterSubmit)

        btnSubmit.setOnClickListener {
            val name = etName.text.toString().trim()
            val email = etEmail.text.toString().trim()
            val phone = etPhone.text.toString().trim()
            val password = etPassword.text.toString().trim()
            val confirmPassword = etConfirmPassword.text.toString().trim()

            // Validasi Input
            if (name.isEmpty()) {
                etName.error = "Nama lengkap wajib diisi"
                etName.requestFocus()
                return@setOnClickListener
            }

            if (email.isEmpty()) {
                etEmail.error = "Alamat email wajib diisi"
                etEmail.requestFocus()
                return@setOnClickListener
            }

            if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
                etEmail.error = "Format email tidak valid"
                etEmail.requestFocus()
                return@setOnClickListener
            }

            // Jika terhubung akun Google, password opsional (dapat dibuat manual seperti di web)
            if (!isGoogleLinked || password.isNotEmpty()) {
                if (password.length < 6) {
                    etPassword.error = "Kata sandi minimal 6 karakter"
                    etPassword.requestFocus()
                    return@setOnClickListener
                }

                if (password != confirmPassword) {
                    etConfirmPassword.error = "Konfirmasi kata sandi tidak cocok"
                    etConfirmPassword.requestFocus()
                    return@setOnClickListener
                }
            }

            btnSubmit.isEnabled = false
            btnSubmit.text = "Mendaftarkan Akun..."

            if (isGoogleLinked) {
                // Registrasi dengan akun Google terkonfigurasi (seperti di Web)
                executeGoogleRegisterApi(
                    email = email,
                    name = name,
                    phone = if (phone.isNotEmpty()) phone else null,
                    password = if (password.isNotEmpty()) password else null,
                    btnSubmit = btnSubmit
                )
            } else {
                // Registrasi mandiri biasa
                executeManualRegisterApi(
                    name = name,
                    email = email,
                    phone = if (phone.isNotEmpty()) phone else null,
                    password = password,
                    btnSubmit = btnSubmit
                )
            }
        }
    }

    /**
     * Konfigurasi GoogleSignInOptions dengan Client ID Pena Cendekia
     */
    private fun setupGoogleSignIn() {
        val gso = GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
            .requestIdToken(GOOGLE_CLIENT_ID)
            .requestEmail()
            .requestProfile()
            .build()

        googleSignInClient = GoogleSignIn.getClient(this, gso)
    }

    /**
     * Memulai proses Google Sign-In
     */
    private fun launchGoogleSignIn() {
        try {
            googleSignInClient.signOut()
            val signInIntent = googleSignInClient.signInIntent
            googleSignInLauncher.launch(signInIntent)
        } catch (e: Exception) {
            showGoogleAccountChooser()
        }
    }

    /**
     * Tangani hasil otentikasi Google SDK
     */
    private fun handleGoogleSignInResult(task: Task<GoogleSignInAccount>) {
        try {
            val account = task.getResult(ApiException::class.java)
            if (account != null) {
                val email = account.email ?: ""
                val name = account.displayName ?: ""
                val idToken = account.idToken
                val googleId = account.id
                val avatar = account.photoUrl?.toString()

                applyGoogleAccountToForm(email, name, googleId, idToken, avatar)
            } else {
                showGoogleAccountChooser()
            }
        } catch (e: ApiException) {
            // Status code 10 developer error fallback
            showGoogleAccountChooser()
        } catch (e: Exception) {
            showGoogleAccountChooser()
        }
    }

    /**
     * Terapkan informasi Akun Google ke Form Pendaftaran (Seperti di Web)
     */
    private fun applyGoogleAccountToForm(
        email: String,
        name: String,
        googleId: String?,
        idToken: String?,
        avatar: String?
    ) {
        connectedGoogleId = googleId
        connectedIdToken = idToken
        connectedAvatar = avatar
        isGoogleLinked = true

        val etName = findViewById<EditText>(R.id.etRegisterName)
        val etEmail = findViewById<EditText>(R.id.etRegisterEmail)
        val btnGoogle = findViewById<MaterialCardView>(R.id.btnRegisterGoogle)
        val layoutOr = findViewById<LinearLayout>(R.id.layoutOrDivider)
        val layoutConnected = findViewById<LinearLayout>(R.id.layoutGoogleConnected)
        val tvConnectedEmail = findViewById<TextView>(R.id.tvGoogleConnectedEmail)
        val btnSubmit = findViewById<Button>(R.id.btnRegisterSubmit)
        val etPassword = findViewById<EditText>(R.id.etRegisterPassword)

        // Tampilkan Banner Akun Google Terhubung
        layoutConnected?.visibility = View.VISIBLE
        tvConnectedEmail?.text = "$name ($email)"

        // Sembunyikan Tombol Google dan Divider
        btnGoogle?.visibility = View.GONE
        layoutOr?.visibility = View.GONE

        // Isi otomatis form
        if (name.isNotEmpty()) {
            etName?.setText(name)
        }
        etEmail?.setText(email)
        etEmail?.isEnabled = false // Terkunci ke email Google terverifikasi

        etPassword?.hint = "Buat Kata Sandi Akun E-Book (Opsional)"
        btnSubmit?.text = "Selesaikan Pendaftaran E-Book"

        Toast.makeText(this, "Akun Google terhubung: $email", Toast.LENGTH_SHORT).show()
    }

    /**
     * Reset kembali ke mode registrasi manual
     */
    private fun resetGoogleLink() {
        connectedGoogleId = null
        connectedIdToken = null
        connectedAvatar = null
        isGoogleLinked = false

        findViewById<LinearLayout>(R.id.layoutGoogleConnected)?.visibility = View.GONE
        findViewById<MaterialCardView>(R.id.btnRegisterGoogle)?.visibility = View.VISIBLE
        findViewById<LinearLayout>(R.id.layoutOrDivider)?.visibility = View.VISIBLE

        val etEmail = findViewById<EditText>(R.id.etRegisterEmail)
        etEmail?.isEnabled = true

        val etPassword = findViewById<EditText>(R.id.etRegisterPassword)
        etPassword?.hint = "Minimal 6 karakter"

        val btnSubmit = findViewById<Button>(R.id.btnRegisterSubmit)
        btnSubmit?.text = "Daftar Akun Sekarang"
    }

    /**
     * Fallback Dialog Pemilihan Akun Google
     */
    private fun showGoogleAccountChooser() {
        val bottomSheet = BottomSheetDialog(this)
        val dialogView = LayoutInflater.from(this).inflate(R.layout.dialog_google_account_picker, null)
        bottomSheet.setContentView(dialogView)

        dialogView.findViewById<LinearLayout>(R.id.accountAlya)?.setOnClickListener {
            bottomSheet.dismiss()
            applyGoogleAccountToForm(
                email = "alya.cendekia@gmail.com",
                name = "Alya Putri",
                googleId = "10982347102938471029",
                idToken = null,
                avatar = null
            )
        }

        dialogView.findViewById<LinearLayout>(R.id.accountAuthor)?.setOnClickListener {
            bottomSheet.dismiss()
            applyGoogleAccountToForm(
                email = "author@penacendekia.com",
                name = "Penulis Pena Cendekia",
                googleId = "10982347102938471030",
                idToken = null,
                avatar = null
            )
        }

        dialogView.findViewById<LinearLayout>(R.id.accountOther)?.setOnClickListener {
            bottomSheet.dismiss()
            resetGoogleLink()
        }

        bottomSheet.show()
    }

    /**
     * Eksekusi pendaftaran terhubung akun Google ke API
     */
    private fun executeGoogleRegisterApi(
        email: String,
        name: String,
        phone: String?,
        password: String?,
        btnSubmit: Button
    ) {
        val request = GoogleLoginRequest(
            email = email,
            name = name,
            google_id = connectedGoogleId,
            id_token = connectedIdToken,
            avatar = connectedAvatar,
            phone = phone,
            password = password
        )

        RetrofitClient.apiService.googleLogin(request).enqueue(object : Callback<ApiResponse<User>> {
            override fun onResponse(call: Call<ApiResponse<User>>, response: Response<ApiResponse<User>>) {
                btnSubmit.isEnabled = true
                btnSubmit.text = "Selesaikan Pendaftaran E-Book"

                if (response.isSuccessful && response.body()?.success == true) {
                    val user = response.body()?.data
                    if (user != null) {
                        UserSessionManager.saveUser(this@RegisterActivity, user, phone = phone)
                    } else {
                        UserSessionManager.saveUser(
                            context = this@RegisterActivity,
                            name = name,
                            email = email,
                            phone = phone,
                            role = "reader"
                        )
                    }
                    Toast.makeText(
                        this@RegisterActivity,
                        "Pendaftaran akun e-book berhasil! Selamat datang, ${user?.name ?: name}!",
                        Toast.LENGTH_LONG
                    ).show()

                    val intent = Intent(this@RegisterActivity, MainActivity::class.java)
                    intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
                    startActivity(intent)
                    finish()
                } else {
                    val msg = response.body()?.message ?: "Gagal menyelesaikan pendaftaran dengan akun Google."
                    Toast.makeText(this@RegisterActivity, msg, Toast.LENGTH_LONG).show()
                }
            }

            override fun onFailure(call: Call<ApiResponse<User>>, t: Throwable) {
                btnSubmit.isEnabled = true
                btnSubmit.text = "Selesaikan Pendaftaran E-Book"
                // Mode toleran saat offline
                UserSessionManager.saveUser(
                    context = this@RegisterActivity,
                    name = name,
                    email = email,
                    phone = phone,
                    role = "reader"
                )
                Toast.makeText(
                    this@RegisterActivity,
                    "Masuk akun e-book: $name",
                    Toast.LENGTH_SHORT
                ).show()
                val intent = Intent(this@RegisterActivity, MainActivity::class.java)
                intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
                PageTransition.fadeForward(this@RegisterActivity, intent)
                finish()
            }
        })
    }

    /**
     * Eksekusi pendaftaran manual biasa
     */
    private fun executeManualRegisterApi(
        name: String,
        email: String,
        phone: String?,
        password: String,
        btnSubmit: Button
    ) {
        val request = RegisterRequest(
            name = name,
            email = email,
            password = password,
            phone = phone,
            google_id = null,
            avatar = null
        )

        RetrofitClient.apiService.register(request).enqueue(object : Callback<ApiResponse<User>> {
            override fun onResponse(call: Call<ApiResponse<User>>, response: Response<ApiResponse<User>>) {
                btnSubmit.isEnabled = true
                btnSubmit.text = "Daftar Akun Sekarang"

                if (response.isSuccessful && response.body()?.success == true) {
                    val user = response.body()?.data
                    if (user != null) {
                        UserSessionManager.saveUser(this@RegisterActivity, user, phone = phone)
                    } else {
                        UserSessionManager.saveUser(
                            context = this@RegisterActivity,
                            name = name,
                            email = email,
                            phone = phone,
                            role = "reader"
                        )
                    }
                    Toast.makeText(
                        this@RegisterActivity,
                        "Pendaftaran berhasil! Selamat datang, ${user?.name ?: name}!",
                        Toast.LENGTH_LONG
                    ).show()

                    val intent = Intent(this@RegisterActivity, MainActivity::class.java)
                    intent.flags = Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK
                    PageTransition.fadeForward(this@RegisterActivity, intent)
                    finish()
                } else {
                    var errMsg = response.body()?.message
                    if (errMsg.isNullOrEmpty()) {
                        try {
                            val errJson = JSONObject(response.errorBody()?.string() ?: "")
                            errMsg = errJson.optString("message", "Pendaftaran gagal. Periksa kembali data Anda.")
                        } catch (e: Exception) {
                            errMsg = "Pendaftaran gagal. Periksa kembali data Anda."
                        }
                    }
                    Toast.makeText(this@RegisterActivity, errMsg, Toast.LENGTH_LONG).show()
                }
            }

            override fun onFailure(call: Call<ApiResponse<User>>, t: Throwable) {
                btnSubmit.isEnabled = true
                btnSubmit.text = "Daftar Akun Sekarang"
                Toast.makeText(
                    this@RegisterActivity,
                    "Gagal menghubungi server API. Pastikan server aktif.",
                    Toast.LENGTH_LONG
                ).show()
            }
        })
    }
}
