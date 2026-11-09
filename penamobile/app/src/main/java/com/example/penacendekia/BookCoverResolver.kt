package com.example.penacendekia

import com.example.penacendekia.data.model.Book

object BookCoverResolver {
    fun localCover(book: Book): Int? {
        val title = normalize(book.title)
        return when {
            "accounting" in title -> R.drawable.img_accounting
            "konstruksi" in title -> R.drawable.img_konstruksi
            "kepemimpinan" in title -> R.drawable.img_kepemimpinan_cover
            "digitalisasi" in title -> R.drawable.img_digitalisasi_cover
            "administrasi" in title -> R.drawable.img_administrasi_cover
            "humas" in title -> R.drawable.img_manajemen_humas
            "cacing" in title -> R.drawable.img_penyakit_cacing
            "jurnalistik" in title -> R.drawable.img_pengantar_jurnalistik
            "penjaminan mutu" in title || "pesantren" in title -> R.drawable.img_penjaminan_mutu
            "animal welfare" in title || "welfare" in title -> R.drawable.img_animal_welfare
            "camel" in title || "unta" in title -> R.drawable.img_camel_perspective
            else -> null
        }
    }

    fun placeholder(book: Book): Int =
        localCover(book) ?: R.drawable.img_banner_book

    private fun normalize(value: String): String =
        java.text.Normalizer.normalize(value, java.text.Normalizer.Form.NFD)
            .replace(Regex("\\p{Mn}+"), "")
            .lowercase()
            .replace(Regex("[^\\p{L}\\p{N}]+"), " ")
            .trim()
}
