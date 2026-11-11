package com.example.penacendekia.data.repository

import android.content.Context
import com.example.penacendekia.R
import com.example.penacendekia.data.cache.AppDiskCache
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.model.HomeData

object BookCatalogRepository {

    // In-memory cache agar perpindahan antar tab Beranda & Katalog berjalan instan (0 detik tanpa jeda network)
    var cachedHomeData: HomeData? = null
    var cachedServerBooks: List<Book>? = null

    /**
     * Memuat HomeData dari RAM, atau dari disk (SharedPreferences) jika aplikasi baru saja dibuka ulang.
     */
    fun getOrLoadHomeData(context: Context): HomeData? {
        if (cachedHomeData != null) return cachedHomeData
        val diskData = AppDiskCache.getHomeData(context)
        if (diskData != null) {
            cachedHomeData = diskData
        }
        return cachedHomeData
    }

    /**
     * Menyimpan HomeData ke RAM dan persistensi disk secara otomatis.
     */
    fun setHomeData(context: Context, data: HomeData) {
        cachedHomeData = data
        AppDiskCache.saveHomeData(context, data)
    }

    /**
     * Memuat buku katalog dari RAM atau dari disk.
     */
    fun getOrLoadCatalogBooks(context: Context): List<Book>? {
        if (cachedServerBooks != null) return cachedServerBooks
        val diskBooks = AppDiskCache.getCatalogBooks(context)
        if (diskBooks != null) {
            cachedServerBooks = diskBooks
        }
        return cachedServerBooks
    }

    /**
     * Menyimpan buku katalog ke RAM dan disk.
     */
    fun setCatalogBooks(context: Context, books: List<Book>) {
        cachedServerBooks = books
        AppDiskCache.saveCatalogBooks(context, books)
    }

    val ALL_11_BOOKS: List<Book> = listOf(
        Book(
            id = 1,
            type = "ebook",
            title = "Kepemimpinan Visioner Di Lembaga Pendidikan Islam",
            subtitle = "Strategi Transformasi Madrasah & Pesantren Unggul di Era Abad 21",
            author = "Dr. Ahmad Zain Sarnoto",
            editor = "Dr. H. Muh. Solihuddin, M.H.I",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Agama",
            isbn = "978-623-8123-11-3",
            year = "2024",
            pages = "xii + 216 hlm",
            size = "15.5 x 23 cm",
            synopsis = "Membahas konsep dan strategi kepemimpinan visioner dalam mentransformasi lembaga pendidikan Islam agar memiliki keunggulan kompetitif, integrasi nilai moderasi beragama, serta kapabilitas tata kelola modern di era digital.",
            coverResId = R.drawable.img_kepemimpinan_visioner_cover,
            rating = 4.9,
            rating_count = 128,
            price = 59000.0,
            formatted_price = "Rp 59.000",
            is_free = false,
            is_read_ready = true
        ),
        Book(
            id = 2,
            type = "ebook",
            title = "Digitalisasi Pendidikan Islam",
            subtitle = "Konsep, Strategi, dan Implementasi Pembelajaran Abad 21",
            author = "Dr. Hj. Siti Yumnah, M.Pd.I., Muhammad Sulaiman",
            editor = "Chairunnisa Djayadin, S.Pd., M.A",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Teknologi",
            isbn = "978-623-8123-04-5",
            year = "2024",
            pages = "xii + 230 hlm",
            size = "15.5 x 23 cm",
            synopsis = "Panduan terapan integrasi teknologi digital dalam pengajaran pendidikan Islam. Membahas model blended learning, pemanfaatan AI dalam madrasah, pengembangan e-modul interaktif, hingga kesiapan literasi digital para pendidik.",
            coverResId = R.drawable.img_digitalisasi_pendidikan_islam_cover,
            rating = 5.0,
            rating_count = 142,
            price = 0.0,
            formatted_price = "Gratis",
            is_free = true,
            is_read_ready = true
        ),
        Book(
            id = 3,
            type = "ebook",
            title = "Administrasi Rekam Medis",
            subtitle = "Tata Kelola Rekam Medis Modern & Digital di Pelayanan Kesehatan",
            author = "Dr. Aries Wahyuningsih, S.Kep., Ns., M.Kes, Indraningrum Fitria, dkk.",
            editor = "Mangindara, S.K.M., M.Kes",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Kesehatan",
            isbn = "978-623-8123-01-4",
            year = "2024",
            pages = "xii + 214 hlm",
            size = "15.5 x 23 cm (UNESCO)",
            synopsis = "Buku ajar komprehensif yang dirancang untuk akademisi, mahasiswa, dan praktisi manajemen informasi kesehatan. Membedah tata kelola rekam medis manual hingga Rekam Medis Elektronik (RME) modern, kerahasiaan data pasien, standar akreditasi rumah sakit, serta sistem klasifikasi kodefikasi klinis.",
            coverResId = R.drawable.img_administrasi_rekam_medis,
            rating = 4.9,
            rating_count = 98,
            price = 65000.0,
            formatted_price = "Rp 65.000",
            is_free = false,
            is_read_ready = true
        ),
        Book(
            id = 4,
            type = "ebook",
            title = "Foundations English For Accounting",
            subtitle = "Practical English for Financial Reports & Business Communication",
            author = "M. Masqotul Imam Romadlani, Eka Susylowati, Meria Zakiyah Alfisuma, Fitriyatuz Zakiyah, Luthfi Awwalia",
            editor = "Tim Bahasa Pena Cendekia",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Bisnis",
            isbn = "978-623-8123-07-6",
            year = "2024",
            pages = "x + 184 hlm",
            size = "15.5 x 23 cm",
            synopsis = "Buku ajar bahasa Inggris spesifik untuk bidang akuntansi dan keuangan, memuat kosakata profesional, penyusunan laporan keuangan internasional, analisis neraca, hingga simulasi presentasi bisnis global.",
            coverResId = R.drawable.img_foundation_english_for_accounting,
            rating = 4.8,
            rating_count = 85,
            price = 49000.0,
            formatted_price = "Rp 49.000",
            is_free = false,
            is_read_ready = true
        ),
        Book(
            id = 5,
            type = "ebook",
            title = "Konstruksi Teori Belajar Pendidikan Dasar Untuk Mewujudkan Pembelajaran Berkualitas",
            subtitle = "Kajian Komprehensif Teori Behavioristik, Kognitif, Konstruktivistik & Humanistik",
            author = "Tim Penulis Pena Cendekia",
            editor = "Dr. Hj. Siti Yumnah, M.Pd.I",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Pendidikan",
            isbn = "978-623-8123-08-3",
            year = "2024",
            pages = "xiv + 208 hlm",
            size = "15.5 x 23 cm",
            synopsis = "Mengkaji paradigma teori belajar behavioristik, kognitif, konstruktivistik, dan humanistik yang diadaptasi secara khusus untuk kurikulum dan karakteristik peserta didik tingkat sekolah dasar guna mewujudkan proses pembelajaran yang bermakna dan berkualitas.",
            coverResId = R.drawable.img_konstruksi_teori_belajar_pendidikan_dasar,
            rating = 4.9,
            rating_count = 110,
            price = 55000.0,
            formatted_price = "Rp 55.000",
            is_free = false,
            is_read_ready = true
        ),
        Book(
            id = 6,
            type = "ebook",
            title = "Manajemen Humas di Sekolah",
            subtitle = "Membangun Kemitraan Strategis Lembaga Pendidikan dengan Masyarakat",
            author = "Dr. Ahmad Zain Sarnoto",
            editor = "Ariza Qurrata A'yun",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Umum",
            isbn = "978-623-8123-05-2",
            year = "2024",
            pages = "x + 196 hlm",
            size = "15.5 x 23 cm",
            synopsis = "Memberikan panduan operasional dalam mengelola hubungan masyarakat di lingkungan sekolah, strategi pencitraan positif, penanganan krisis reputasi, hingga optimalisasi media sosial sekolah sebagai sarana komunikasi publik yang efektif.",
            coverResId = R.drawable.img_manajemen_humas,
            rating = 4.8,
            rating_count = 76,
            price = 52000.0,
            formatted_price = "Rp 52.000",
            is_free = false,
            is_read_ready = true
        ),
        Book(
            id = 7,
            type = "ebook",
            title = "Penyakit Cacing, Protozoa Dan Gizi Pada Peternakan Unggas Sebagai Dasar Kajian Pada Embrio Hewan",
            subtitle = "Patologi Parasit dan Nutrisi Unggas Menuju Kajian Embriologi Komparatif",
            author = "Maslichah Mafruchati",
            editor = "Tim Saintifik Veteriner Pena Cendekia",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Kedokteran Hewan",
            isbn = "978-623-8123-06-9",
            year = "2024",
            pages = "xvi + 234 hlm",
            size = "15.5 x 23 cm",
            synopsis = "Cacing, Protozoa, dan Penyakit Nutrisi pada Peternakan Unggas sebagai Dasar Studi Embrio Hewan memberikan kajian menyeluruh tentang gangguan parasit dan nutrisi yang secara signifikan memengaruhi kesehatan dan perkembangan unggas.",
            coverResId = R.drawable.img_penyakit_cacing,
            rating = 5.0,
            rating_count = 92,
            price = 72000.0,
            formatted_price = "Rp 72.000",
            is_free = false,
            is_read_ready = true
        ),
        Book(
            id = 8,
            type = "ebook",
            title = "Pengantar Jurnalistik",
            subtitle = "Teori, Praktik, dan Etika Peliputan Berita di Era Multiplatform",
            author = "Kartika Bina Kasih, Nurliya Ni’matul Rohmah, Maulana Syarifudin, Fitria Akmal, Rasman, Fathurrijal, Fikry Zahria Emeraldien, Miftahul Khoirin, Layung Paramesti Martha",
            editor = "Ariza Qurrata A'yun",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Sastra",
            isbn = "978-623-8123-09-0",
            year = "2024",
            pages = "xii + 198 hlm",
            size = "15.5 x 23 cm",
            synopsis = "Buku panduan dasar jurnalistik mencakup kaidah penulisan berita, wawancara investigatif, kode etik jurnalistik, dan strategi adaptasi reporter dalam era jurnalisme digital multiplatform.",
            coverResId = R.drawable.img_pengantar_jurnalistik,
            rating = 4.9,
            rating_count = 104,
            price = 48000.0,
            formatted_price = "Rp 48.000",
            is_free = false,
            is_read_ready = true
        ),
        Book(
            id = 9,
            type = "ebook",
            title = "Penjaminan Mutu Dan Keunggulan Kompetitif Pesantren",
            subtitle = "Sistem Manajemen Mutu Terpadu di Lembaga Pendidikan Islam",
            author = "Dr. Ahmad Zain Sarnoto, M.Pd & Tim Riset Pendidikan",
            editor = "Prof. Dr. H. Saiful Jazil, M.Ag",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Referensi",
            isbn = "978-623-8123-10-6",
            year = "2024",
            pages = "xiv + 240 hlm",
            size = "15.5 x 23 cm",
            synopsis = "Membahas model Sistem Penjaminan Mutu Internal (SPMI) yang dikontekstualisasikan untuk kultur pesantren dan madrasah, penetapan standar mutu lulusan, akreditasi, dan audit mutu akademik berkelanjutan.",
            coverResId = R.drawable.img_penjaminan_mutu,
            rating = 4.9,
            rating_count = 88,
            price = 60000.0,
            formatted_price = "Rp 60.000",
            is_free = false,
            is_read_ready = true
        ),
        Book(
            id = 10,
            type = "ebook",
            title = "Animal Welfare Perspective",
            subtitle = "Prinsip Kesejahteraan Hewan dalam Praktik Veteriner dan Peternakan Modern",
            author = "Dr. Maslichah Mafruchati, drh., M.Si",
            editor = "Tim Saintifik Veteriner",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Kedokteran Hewan",
            isbn = "978-623-8123-12-0",
            year = "2024",
            pages = "xii + 224 hlm",
            size = "15.5 x 23 cm",
            synopsis = "Kajian multidisipliner tentang lima prinsip kebebasan hewan (Five Freedoms), etika biomedis perlakuan hewan uji laboratorium, serta regulasi standar kesejahteraan ternak komersial dalam rantai pasok pangan hewani.",
            coverResId = R.drawable.img_animal_welfare_perspective,
            rating = 5.0,
            rating_count = 79,
            price = 68000.0,
            formatted_price = "Rp 68.000",
            is_free = false,
            is_read_ready = true
        ),
        Book(
            id = 11,
            type = "ebook",
            title = "Camel Animal Perspective",
            subtitle = "Biologi Reproduksi, Adaptasi Ekologis, dan Potensi Biomedis Unta",
            author = "Dr. Maslichah Mafruchati, drh., M.Si & Tim Peneliti Embriologi",
            editor = "Tim Saintifik Veteriner",
            publisher = "PT. Pena Cendekia Pustaka",
            category = "E-Book · Kedokteran Hewan",
            isbn = "978-623-8123-13-7",
            year = "2024",
            pages = "xii + 210 hlm",
            size = "15.5 x 23 cm",
            synopsis = "Eksplorasi komprehensif mengenai keunikan fisiologis, sistem imun spesifik (nanobody), dan biologi adaptasi gurun unta, serta implikasi mutakhirnya dalam riset embriologi dan kedokteran komparatif internasional.",
            coverResId = R.drawable.img_camel_animal_perspective,
            rating = 4.9,
            rating_count = 83,
            price = 68000.0,
            formatted_price = "Rp 68.000",
            is_free = false,
            is_read_ready = true
        )
    )

    fun getBooks(category: String? = null, search: String? = null): List<Book> {
        var list = ALL_11_BOOKS
        if (!category.isNullOrBlank() && !category.equals("Semua", ignoreCase = true)) {
            list = list.filter { matchesCategory(it, category) }
        }
        if (!search.isNullOrBlank()) {
            val q = search.trim().lowercase()
            list = list.filter {
                it.title.lowercase().contains(q) ||
                    it.author.lowercase().contains(q) ||
                    it.category?.lowercase()?.contains(q) == true ||
                    it.isbn?.lowercase()?.contains(q) == true
            }
        }
        return list
    }

    fun findBook(titleOrId: String?): Book? {
        if (titleOrId.isNullOrBlank()) return null
        val idVal = titleOrId.toIntOrNull()
        if (idVal != null && idVal > 0) {
            val found = ALL_11_BOOKS.find { it.id == idVal }
            if (found != null) return found
        }
        val q = titleOrId.trim().lowercase()
        return ALL_11_BOOKS.find {
            it.title.lowercase().contains(q) || q.contains(it.title.lowercase())
        }
    }

    fun enrichBook(book: Book): Book {
        val matchedCover = com.example.penacendekia.BookCoverResolver.localCoverByTitle(book.title)
        val known = ALL_11_BOOKS.find {
            normalizeTitle(it.title) == normalizeTitle(book.title) ||
                normalizeTitle(it.title).contains(normalizeTitle(book.title)) ||
                normalizeTitle(book.title).contains(normalizeTitle(it.title))
        }
        val coverRes = matchedCover ?: known?.coverResId

        if (known == null) {
            return book.copy(
                type = "ebook",
                coverResId = coverRes,
                category = if (book.category.isNullOrBlank()) "E-Book" else if (!book.category.contains("E-Book", ignoreCase = true)) "E-Book · ${book.category}" else book.category
            )
        }

        val resolvedCategory = known.category ?: if (book.category.isNullOrBlank()) "E-Book" else if (!book.category.contains("E-Book", ignoreCase = true)) "E-Book · ${book.category}" else book.category

        return book.copy(
            type = "ebook",
            coverResId = coverRes,
            subtitle = book.subtitle.takeUnless { it.isNullOrBlank() } ?: known.subtitle,
            author = book.author.takeUnless { it.isBlank() } ?: known.author,
            editor = book.editor.takeUnless { it.isNullOrBlank() } ?: known.editor,
            publisher = book.publisher.takeUnless { it.isNullOrBlank() } ?: known.publisher,
            category = resolvedCategory,
            isbn = book.isbn.takeUnless { it.isNullOrBlank() } ?: known.isbn,
            year = book.year.takeUnless { it.isNullOrBlank() } ?: known.year,
            pages = book.pages.takeUnless { it.isNullOrBlank() } ?: known.pages,
            size = book.size.takeUnless { it.isNullOrBlank() } ?: known.size,
            synopsis = book.synopsis.takeUnless { it.isNullOrBlank() } ?: known.synopsis,
            rating = if (book.rating > 0) book.rating else known.rating,
            rating_count = if (book.rating_count > 0) book.rating_count else known.rating_count,
            price = if (book.price > 0) book.price else known.price,
            formatted_price = book.formatted_price.takeUnless { it.isNullOrBlank() } ?: known.formatted_price,
            file_url = book.file_url ?: known.file_url,
            download_url = book.download_url ?: known.download_url,
            sample_url = book.sample_url ?: known.sample_url,
            cover_url = book.cover_url ?: known.cover_url,
            is_free = if (book.price > 0) false else book.is_free
        )
    }

    private fun matchesCategory(book: Book, category: String): Boolean {
        val c = category.trim().lowercase()
        val bookCat = book.category?.lowercase().orEmpty()
        val bookTitle = book.title.lowercase()
        val bookType = book.type?.lowercase().orEmpty()

        // Semua buku di aplikasi mobile masuk ke kategori E-Book / Digital
        if (c == "e-book" || c == "ebook" || c == "digital") {
            return true
        }

        return when (c) {
            "pendidikan" -> bookCat.contains("pendidikan") || bookTitle.contains("pendidikan") || bookTitle.contains("belajar") || bookTitle.contains("mutu")
            "referensi" -> bookCat.contains("referensi") || bookCat.contains("kesehatan") || bookTitle.contains("jurnalistik") || bookTitle.contains("mutu") || bookTitle.contains("perspective")
            "sastra" -> bookCat.contains("sastra") || bookTitle.contains("jurnalistik")
            "umum" -> bookCat.contains("umum") || bookTitle.contains("humas") || bookTitle.contains("jurnalistik")
            "agama" -> bookCat.contains("agama") || bookTitle.contains("islam") || bookTitle.contains("pesantren")
            "buku ajar" -> bookCat.contains("buku ajar") || bookTitle.contains("administrasi") || bookTitle.contains("accounting") || bookTitle.contains("teori") || bookTitle.contains("cacing") || bookTitle.contains("camel")
            "kedokteran hewan" -> bookCat.contains("kedokteran hewan") || bookTitle.contains("cacing") || bookTitle.contains("animal") || bookTitle.contains("camel")
            "kesehatan" -> bookCat.contains("kesehatan") || bookTitle.contains("rekam medis")
            "teknologi" -> bookCat.contains("teknologi") || bookTitle.contains("digitalisasi")
            "bisnis" -> bookCat.contains("bisnis") || bookTitle.contains("accounting")
            else -> bookCat.contains(c) || bookTitle.contains(c)
        }
    }

    fun normalizeTitle(text: String): String =
        text.lowercase()
            .replace(Regex("[^a-z0-9]"), "")
}
