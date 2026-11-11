package com.example.penacendekia

import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.repository.BookCatalogRepository
import org.junit.Assert.assertEquals
import org.junit.Assert.assertNotNull
import org.junit.Test

class ExampleUnitTest {

    @Test
    fun testAll11BooksCoverMappingMatchesExactly() {
        val expectedMappings = listOf(
            "Kepemimpinan Visioner Di Lembaga Pendidikan Islam" to R.drawable.img_kepemimpinan_visioner_cover,
            "Digitalisasi Pendidikan Islam" to R.drawable.img_digitalisasi_pendidikan_islam_cover,
            "Administrasi Rekam Medis" to R.drawable.img_administrasi_rekam_medis,
            "Foundations English For Accounting" to R.drawable.img_foundation_english_for_accounting,
            "Konstruksi Teori Belajar Pendidikan Dasar Untuk Mewujudkan Pembelajaran Berkualitas" to R.drawable.img_konstruksi_teori_belajar_pendidikan_dasar,
            "Manajemen Humas di Sekolah" to R.drawable.img_manajemen_humas,
            "Penyakit Cacing, Protozoa Dan Gizi Pada Peternakan Unggas Sebagai Dasar Kajian Pada Embrio Hewan" to R.drawable.img_penyakit_cacing,
            "Pengantar Jurnalistik" to R.drawable.img_pengantar_jurnalistik,
            "Penjaminan Mutu Dan Keunggulan Kompetitif Pesantren" to R.drawable.img_penjaminan_mutu,
            "Animal Welfare Perspective" to R.drawable.img_animal_welfare_perspective,
            "Camel Animal Perspective" to R.drawable.img_camel_animal_perspective
        )

        for ((title, expectedResId) in expectedMappings) {
            val resIdByTitle = BookCoverResolver.localCoverByTitle(title)
            assertEquals("localCoverByTitle mismatch for: $title", expectedResId, resIdByTitle)

            val dummyBook = Book(id = 999, title = title, author = "Author")
            val resolved = BookCoverResolver.resolveCover(dummyBook)
            assertEquals("resolveCover mismatch for dummy book: $title", expectedResId, resolved)
        }
    }

    @Test
    fun testRepositoryAll11BooksCoverResId() {
        assertEquals("Total local catalog books must be exactly 11", 11, BookCatalogRepository.ALL_11_BOOKS.size)
        for (book in BookCatalogRepository.ALL_11_BOOKS) {
            val coverRes = BookCoverResolver.resolveCover(book)
            assertNotNull("Cover must not be null for book: ${book.title}", coverRes)
            val byTitle = BookCoverResolver.localCoverByTitle(book.title)
            assertEquals("Repository book coverResId must match byTitle for: ${book.title}", byTitle, coverRes)
        }
    }

    @Test
    fun testVariationsAndServerTitlesMatchCorrectly() {
        assertEquals(R.drawable.img_administrasi_rekam_medis, BookCoverResolver.localCoverByTitle("Administrasi Rekam Medis"))
        assertEquals(R.drawable.img_digitalisasi_pendidikan_islam_cover, BookCoverResolver.localCoverByTitle("Digitalisasi Pendidikan Islam"))
        assertEquals(R.drawable.img_konstruksi_teori_belajar_pendidikan_dasar, BookCoverResolver.localCoverByTitle("Kontruksi Teori Belajar"))
        assertEquals(R.drawable.img_foundation_english_for_accounting, BookCoverResolver.localCoverByTitle("Foundations English For Accounting"))
        assertEquals(R.drawable.img_animal_welfare_perspective, BookCoverResolver.localCoverByTitle("Animal Welfare Perspective"))
        assertEquals(R.drawable.img_camel_animal_perspective, BookCoverResolver.localCoverByTitle("Camel Animal Perspective"))
        assertEquals(R.drawable.img_manajemen_humas, BookCoverResolver.localCoverByTitle("Manajemen Humas di Sekolah"))
        assertEquals(R.drawable.img_pengantar_jurnalistik, BookCoverResolver.localCoverByTitle("Pengantar Jurnalistik"))
        assertEquals(R.drawable.img_penjaminan_mutu, BookCoverResolver.localCoverByTitle("Penjaminan Mutu Dan Keunggulan Kompetitif Pesantren"))
        assertEquals(R.drawable.img_penyakit_cacing, BookCoverResolver.localCoverByTitle("Penyakit Cacing, Protozoa Dan Gizi Pada Peternakan Unggas"))
    }
}
