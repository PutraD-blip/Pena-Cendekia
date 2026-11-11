package com.example.penacendekia.data.model

import java.io.Serializable

data class Book(
    val id: Int,
    val type: String? = null,
    val title: String,
    val subtitle: String? = null,
    val author: String,
    val editor: String? = null,
    val publisher: String? = null,
    val category: String? = null,
    val isbn: String? = null,
    val year: String? = null,
    val pages: String? = null,
    val size: String? = null,
    val synopsis: String? = null,
    val cover_url: String? = null,
    val coverResId: Int? = null,
    val rating: Double = 4.9,
    val rating_count: Int = 120,
    val price: Double = 0.0,
    val formatted_price: String? = null,
    val is_free: Boolean = true,
    val is_read_ready: Boolean = true,
    val download_url: String? = null,
    val file_url: String? = null,
    val sample_url: String? = null
) : Serializable
