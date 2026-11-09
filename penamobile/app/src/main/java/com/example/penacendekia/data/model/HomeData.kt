package com.example.penacendekia.data.model

data class HomeData(
    val greeting: String? = null,
    val banners: List<BannerItem>? = null,
    val popular_books: List<Book>? = null,
    val latest_book: Book? = null,
    val editor_pick: Book? = null,
    val ebooks: List<Book>? = null,
    val total_books: Int = 0,
    val total_ebooks: Int = 0
)

data class BannerItem(
    val id: Int,
    val title: String,
    val subtitle: String,
    val tag: String
)
