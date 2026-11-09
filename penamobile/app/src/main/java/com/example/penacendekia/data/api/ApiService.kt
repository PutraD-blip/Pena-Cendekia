package com.example.penacendekia.data.api

import com.example.penacendekia.data.model.*
import retrofit2.Call
import retrofit2.http.*

interface ApiService {
    @GET("books")
    fun getBooks(
        @Query("search") search: String? = null,
        @Query("category") category: String? = null,
        @Query("type") type: String? = null,
        @Query("limit") limit: Int? = 50
    ): Call<ApiResponse<List<Book>>>

    @GET("books/{id}")
    fun getBookDetail(
        @Path("id") id: Int
    ): Call<ApiResponse<Book>>

    @GET("home")
    fun getHome(): Call<ApiResponse<HomeData>>

    @GET("categories")
    fun getCategories(): Call<ApiResponse<CategoryResponse>>

    @POST("login")
    fun login(
        @Body body: LoginRequest
    ): Call<ApiResponse<User>>

    @POST("register")
    fun register(
        @Body body: RegisterRequest
    ): Call<ApiResponse<User>>

    @POST("google-login")
    fun googleLogin(
        @Body body: GoogleLoginRequest
    ): Call<ApiResponse<User>>

    @POST("forgot-password")
    fun forgotPassword(
        @Body body: ForgotPasswordRequest
    ): Call<ApiResponse<Any>>

    @POST("reviews")
    fun submitBookReview(
        @Body body: BookReviewSubmission
    ): Call<ApiResponse<BookReviewRecord>>

    @GET("reviews")
    fun getReviews(
        @Query("book_id") bookId: Int? = null,
        @Query("book_title") bookTitle: String? = null
    ): Call<ApiResponse<BookReviewListResponse>>

    @GET("notifications")
    fun getNotifications(): Call<ApiResponse<List<NotificationItem>>>
}
