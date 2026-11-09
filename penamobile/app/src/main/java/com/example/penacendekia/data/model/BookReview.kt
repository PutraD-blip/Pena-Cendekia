package com.example.penacendekia.data.model

data class BookReviewSubmission(
    val book_id: Int?,
    val book_title: String,
    val reviewer_name: String,
    val rating: Int,
    val review_text: String
)

data class BookReviewRecord(
    val id: Int,
    val book_id: Int?,
    val book_title: String,
    val reviewer_name: String,
    val rating: Int,
    val review_text: String,
    val is_approved: Boolean,
    val created_at: String? = null
)

data class BookReviewListResponse(
    val average_rating: Double,
    val total_reviews: Int,
    val reviews: List<BookReviewRecord>
)

data class NotificationItem(
    val id: Int,
    val book_id: Int,
    val title: String,
    val message: String,
    val author: String,
    val category: String? = null,
    val cover_url: String? = null,
    val date: String,
    val type: String
) : java.io.Serializable
