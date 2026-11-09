package com.example.penacendekia.data.model

data class User(
    val id: Int,
    val name: String,
    val email: String,
    val role: String,
    val phone: String? = null
)

data class LoginRequest(
    val email: String,
    val password: String
)

data class GoogleLoginRequest(
    val email: String,
    val name: String? = null,
    val google_id: String? = null,
    val id_token: String? = null,
    val avatar: String? = null,
    val phone: String? = null,
    val password: String? = null
)

data class ForgotPasswordRequest(
    val email: String,
    val new_password: String
)

data class RegisterRequest(
    val name: String,
    val email: String,
    val password: String,
    val phone: String? = null,
    val google_id: String? = null,
    val avatar: String? = null
)
