package com.example.penacendekia

import com.example.penacendekia.data.api.RetrofitClient
import com.example.penacendekia.data.model.Book

object BookCoverResolver {

    /**
     * Resolusi drawable lokal untuk 11 buku resmi.
     * Evaluasi judul dilakukan terlebih dahulu untuk mencegah issue pergeseran
     * ID resource Android (AAPT integer ID) ketika ada file drawable yang di-rename/dihapus/di-cache di disk.
     */
    fun localCover(book: Book): Int? {
        val byTitle = localCoverByTitle(book.title)
        if (byTitle != null) {
            return byTitle
        }
        if (book.coverResId != null && book.coverResId != 0) {
            return book.coverResId
        }
        return null
    }

    /**
     * Pencocokan 100% tepat antara judul buku dengan nama file drawable di res/drawable.
     * Mencakup 11 buku resmi Pena Cendekia:
     * 1. img_administrasi_rekam_medis.png
     * 2. img_animal_welfare_perspective.png
     * 3. img_camel_animal_perspective.png
     * 4. img_digitalisasi_pendidikan_islam_cover.png
     * 5. img_foundation_english_for_accounting.png
     * 6. img_kepemimpinan_visioner_cover.png
     * 7. img_konstruksi_teori_belajar_pendidikan_dasar.png
     * 8. img_manajemen_humas.png
     * 9. img_pengantar_jurnalistik.png
     * 10. img_penjaminan_mutu.png
     * 11. img_penyakit_cacing.png
     */
    fun localCoverByTitle(titleRaw: String?): Int? {
        if (titleRaw.isNullOrBlank()) return null
        val title = normalize(titleRaw)
        return when {
            // 1. Unta / Camel
            "camel" in title || "unta" in title ->
                R.drawable.img_camel_animal_perspective

            // 2. Animal Welfare
            "animal welfare" in title || ("welfare" in title && "camel" !in title) ->
                R.drawable.img_animal_welfare_perspective

            // 3. Kepemimpinan Visioner
            "kepemimpinan" in title || "visioner" in title ->
                R.drawable.img_kepemimpinan_visioner_cover

            // 4. Digitalisasi Pendidikan Islam
            "digitalisasi" in title ->
                R.drawable.img_digitalisasi_pendidikan_islam_cover

            // 5. Administrasi Rekam Medis
            "administrasi" in title || "rekam medis" in title ->
                R.drawable.img_administrasi_rekam_medis

            // 6. English For Accounting
            "accounting" in title || "foundations english" in title || "foundation english" in title || "foundation" in title ->
                R.drawable.img_foundation_english_for_accounting

            // 7. Konstruksi Teori Belajar Pendidikan Dasar
            "konstruksi" in title || "kontruksi" in title || "teori belajar" in title ->
                R.drawable.img_konstruksi_teori_belajar_pendidikan_dasar

            // 8. Manajemen Humas di Sekolah
            "humas" in title || "manajemen humas" in title ->
                R.drawable.img_manajemen_humas

            // 9. Penyakit Cacing & Protozoa
            "cacing" in title || "protozoa" in title ->
                R.drawable.img_penyakit_cacing

            // 10. Pengantar Jurnalistik
            "jurnalistik" in title ->
                R.drawable.img_pengantar_jurnalistik

            // 11. Penjaminan Mutu & Pesantren
            "penjaminan mutu" in title || "keunggulan kompetitif" in title || "pesantren" in title ->
                R.drawable.img_penjaminan_mutu

            // 12. Asuhan Kebidanan Pada Kehamilan
            "asuhan kebidanan" in title || "kehamilan" in title || "kebidanan" in title ->
                R.drawable.img_asuhan_kebidanan_pada_kehamilan

            else -> null
        }
    }

    /**
     * Memuat cover langsung ke ImageView secara aman dan akurat.
     * 1. Jika merupakan 11 buku lokal resmi Pena Cendekia:
     *    Langsung pasang via setImageResource() ke Android OS resource manager.
     *    Metode ini 100% bypass Glide disk cache, sehingga kebal terhadap cache lama
     *    atau pergeseran integer AAPT di memori HP.
     * 2. Jika merupakan buku baru dari server web:
     *    Dimuat menggunakan Glide dari remote cover_url.
     * 3. Jika tidak ada gambar:
     *    Gunakan placeholder resmi Pena Cendekia.
     */
    fun loadCover(imageView: android.widget.ImageView, book: Book) {
        loadCover(imageView, book.title, book.cover_url, book.coverResId)
    }

    fun loadCover(
        imageView: android.widget.ImageView,
        title: String?,
        coverUrl: String?,
        explicitResId: Int? = null
    ) {
        val localRes = localCoverByTitle(title) ?: explicitResId?.takeIf { it != 0 }
        if (localRes != null && localRes != 0) {
            imageView.setImageResource(localRes)
            return
        }

        val remote = coverUrl?.trim()
        if (!remote.isNullOrEmpty() &&
            !remote.contains("default") &&
            !remote.contains("placeholder") &&
            !remote.endsWith("administrasi-rekam-medis.jpg")) {
            val formatted = RetrofitClient.getFormattedImageUrl(remote)
            if (formatted != null) {
                try {
                    com.bumptech.glide.Glide.with(imageView.context)
                        .load(formatted)
                        .placeholder(R.drawable.img_banner_photo)
                        .error(R.drawable.img_banner_photo)
                        .into(imageView)
                    return
                } catch (e: Exception) {
                    e.printStackTrace()
                }
            }
        }

        imageView.setImageResource(R.drawable.img_banner_photo)
    }

    /**
     * Resolusi cerdas sampul buku yang 100% tepat:
     * 1. Jika buku merupakan salah satu dari 11 buku lokal resmi Pena Cendekia,
     *    SELALU prioritaskan cover drawable beresolusi tinggi di res/drawable.
     * 2. Jika merupakan buku baru yang diunggah admin di website,
     *    gunakan remote cover_url dari server jika bukan placeholder bawaan.
     * 3. Jika tidak ada, gunakan placeholder buku Pena Cendekia.
     */
    fun resolveCover(book: Book): Any {
        val local = localCover(book)
        if (local != null) {
            return local
        }
        val remote = book.cover_url?.trim()
        if (!remote.isNullOrEmpty() &&
            !remote.contains("default") &&
            !remote.contains("placeholder") &&
            !remote.endsWith("administrasi-rekam-medis.jpg")) {
            val formatted = RetrofitClient.getFormattedImageUrl(remote)
            if (formatted != null) return formatted
        }
        return placeholder(book)
    }

    fun placeholder(book: Book): Int =
        localCover(book) ?: R.drawable.img_banner_photo

    private fun normalize(value: String): String =
        java.text.Normalizer.normalize(value, java.text.Normalizer.Form.NFD)
            .replace(Regex("\\p{Mn}+"), "")
            .lowercase()
            .replace(Regex("[^\\p{L}\\p{N}]+"), " ")
            .trim()
}
