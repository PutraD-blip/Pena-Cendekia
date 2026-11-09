package com.example.penacendekia.data.model

data class Category(
    val id: Int,
    val name: String,
    val slug: String
)

data class CategoryResponse(
    val master_categories: List<Category>? = null,
    val available_genres: List<String>? = null
)
