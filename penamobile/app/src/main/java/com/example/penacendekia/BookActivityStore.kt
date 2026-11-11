package com.example.penacendekia

import android.content.Context
import android.content.SharedPreferences
import android.graphics.RectF
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.repository.BookCatalogRepository
import org.json.JSONArray
import org.json.JSONObject
import java.text.SimpleDateFormat
import java.util.Date
import java.util.Locale

data class SavedBookReview(val rating: Int, val text: String)
data class SavedPageBookmark(val book: Book, val page: Int)
data class SavedHighlightNote(
    val id: String,
    val bookKey: String,
    val page: Int,
    val rectLeftRatio: Float,
    val rectTopRatio: Float,
    val rectRightRatio: Float,
    val rectBottomRatio: Float,
    val colorHex: String,
    val noteText: String,
    val selectedText: String = "",
    val lineRectsJson: String = "",
    val createdAt: Long = System.currentTimeMillis()
) {
    fun getLineRects(): List<RectF> {
        if (lineRectsJson.isBlank()) {
            return listOf(RectF(rectLeftRatio, rectTopRatio, rectRightRatio, rectBottomRatio))
        }
        return runCatching {
            val arr = JSONArray(lineRectsJson)
            val list = mutableListOf<RectF>()
            for (i in 0 until arr.length()) {
                val item = arr.getJSONArray(i)
                list.add(
                    RectF(
                        item.getDouble(0).toFloat(),
                        item.getDouble(1).toFloat(),
                        item.getDouble(2).toFloat(),
                        item.getDouble(3).toFloat()
                    )
                )
            }
            if (list.isEmpty()) listOf(RectF(rectLeftRatio, rectTopRatio, rectRightRatio, rectBottomRatio)) else list
        }.getOrDefault(listOf(RectF(rectLeftRatio, rectTopRatio, rectRightRatio, rectBottomRatio)))
    }

    companion object {
        fun lineRectsToJson(rects: List<RectF>): String {
            val arr = JSONArray()
            for (r in rects) {
                val item = JSONArray()
                item.put(r.left.toDouble())
                item.put(r.top.toDouble())
                item.put(r.right.toDouble())
                item.put(r.bottom.toDouble())
                arr.put(item)
            }
            return arr.toString()
        }
    }
}
data class ActiveReadingSession(
    val book: Book,
    val currentPage: Int,
    val totalPages: Int,
    val progressPercent: Int,
    val lastReadAt: Long
)
data class FinishedBook(
    val book: Book,
    val finishedAt: Long
)

object BookActivityStore {
    private const val FAVORITES_KEY = "favorites"
    private const val BOOKMARKS_KEY = "page_bookmarks"
    private const val ACTIVE_SESSIONS_KEY = "active_reading_sessions"
    private const val FINISHED_BOOKS_KEY = "finished_reading_books"
    private const val HIGHLIGHTS_KEY = "highlight_notes"

    /**
     * Mengambil SharedPreferences terisolasi khusus untuk akun yang sedang login saat ini.
     * Pengguna baru atau akun berbeda akan memiliki data tersendiri tanpa tercampur dengan data dummy / akun lain.
     * Khusus akun Alya Putri (akun bawaan simulasi/dummy), data demo akan di-seed otomatis untuk keperluan testing.
     */
    private fun getPreferences(context: Context): SharedPreferences {
        val email = UserSessionManager.getUserEmail(context).trim().lowercase()
        val isAlya = email.contains("alya")
        val prefsName = if (email.isNotEmpty()) {
            "book_activity_" + email.replace(Regex("[^a-zA-Z0-9_]"), "_")
        } else {
            "book_activity_guest"
        }
        val prefs = context.getSharedPreferences(prefsName, Context.MODE_PRIVATE)

        // Seed data demo bawaan HANYA jika akun yang login adalah akun Alya Putri
        if (isAlya && !prefs.getBoolean("alya_demo_seeded_v1", false)) {
            seedAlyaDemoData(prefs)
        }

        return prefs
    }

    private fun seedAlyaDemoData(prefs: SharedPreferences) {
        val editor = prefs.edit()

        // 1. Buku-buku katalog untuk data demo
        val bDigitalisasi = BookCatalogRepository.findBook("Digitalisasi Pendidikan") ?: Book(
            id = 4,
            title = "Digitalisasi Pendidikan Islam",
            author = "Dr. Hj. Siti Yumnah, M.Pd.I. Muhammad Sulaiman",
            category = "E-Book · Teknologi",
            pages = "216"
        )
        val bAdministrasi = BookCatalogRepository.findBook("Administrasi Rekam Medis") ?: Book(
            id = 1,
            title = "Administrasi Rekam Medis",
            author = "Dr. Aries Wahyuningsih, S.Kep., Ns., M.Kes.",
            category = "E-Book · Kesehatan",
            pages = "236"
        )
        val bPenyakitCacing = BookCatalogRepository.findBook("Penyakit Cacing") ?: Book(
            id = 2,
            title = "Penyakit Cacing, Protozoa Dan Gizi Pada Peternakan Unggas",
            author = "Maslichah Mafruchati",
            category = "E-Book · Kedokteran Hewan"
        )
        val bKepemimpinan = BookCatalogRepository.findBook("Kepemimpinan Visioner") ?: Book(
            id = 3,
            title = "Kepemimpinan Visioner Di Lembaga Pendidikan Islam",
            author = "Dr. Ahmad Zain Sarnoto",
            category = "E-Book · Agama"
        )
        val bMunakahat = BookCatalogRepository.findBook("Munakahat") ?: Book(
            id = 5,
            title = "Fikih Munakahat",
            author = "Drs. H. Ahmad Fauzi",
            category = "E-Book · Agama"
        )

        // 2. Active Sessions Alya: Digitalisasi (42 dari 216 - 20%) & Administrasi (200 dari 236 - 85%)
        val sessionsArray = JSONArray().apply {
            put(JSONObject().apply {
                put("book", bookToJson(bDigitalisasi))
                put("current_page", 42)
                put("total_pages", 216)
                put("progress_percent", 20)
                put("last_read_at", System.currentTimeMillis() - 3600000L)
            })
            put(JSONObject().apply {
                put("book", bookToJson(bAdministrasi))
                put("current_page", 200)
                put("total_pages", 236)
                put("progress_percent", 85)
                put("last_read_at", System.currentTimeMillis() - 7200000L)
            })
        }
        editor.putString(ACTIVE_SESSIONS_KEY, sessionsArray.toString())

        // 3. Finished Books Alya: Administrasi Rekam Medis & Penyakit Cacing
        val finishedArray = JSONArray().apply {
            put(JSONObject().apply {
                put("book", bookToJson(bAdministrasi))
                put("finished_at", System.currentTimeMillis() - 864000000L)
            })
            put(JSONObject().apply {
                put("book", bookToJson(bPenyakitCacing))
                put("finished_at", System.currentTimeMillis() - 1728000000L)
            })
        }
        editor.putString(FINISHED_BOOKS_KEY, finishedArray.toString())

        // 4. Favorites Alya (5 buku)
        val favoritesSet = mutableSetOf<String>()
        val favBooks = listOf(bDigitalisasi, bAdministrasi, bKepemimpinan, bMunakahat, bPenyakitCacing)
        favBooks.forEachIndexed { idx, book ->
            favoritesSet.add(bookToJson(book).put("saved_at", System.currentTimeMillis() - (idx * 86400000L)).toString())
        }
        editor.putStringSet(FAVORITES_KEY, favoritesSet)

        // 5. Bookmarks Alya (3 halaman)
        val bookmarksList = listOf(
            SavedPageBookmark(bDigitalisasi, 42),
            SavedPageBookmark(bAdministrasi, 120),
            SavedPageBookmark(bKepemimpinan, 75)
        )
        editor.putStringSet(
            BOOKMARKS_KEY,
            bookmarksList.map { bookmark ->
                JSONObject()
                    .put("page", bookmark.page)
                    .put("book", bookToJson(bookmark.book))
                    .toString()
            }.toSet()
        )

        editor.putBoolean("alya_demo_seeded_v1", true)
        editor.apply()
    }

    fun bookKey(book: Book): String {
        val normalizedTitle = book.title.trim().lowercase().replace(Regex("\\s+"), " ")
        return if (normalizedTitle.isNotEmpty()) "title:$normalizedTitle" else "id:${book.id}"
    }

    // ==========================================
    // 1. FAVORIT PENGGUNA
    // ==========================================

    fun toggleFavorite(context: Context, book: Book): Boolean {
        val preferences = getPreferences(context)
        val favorites = preferences.getStringSet(FAVORITES_KEY, emptySet()).orEmpty().toMutableSet()
        val key = bookKey(book)
        val existing = favorites.firstOrNull { favoriteKey(it) == key }
        val isFavorite = if (existing != null) {
            favorites.removeAll { favoriteKey(it) == key }
            false
        } else {
            favorites.add(bookToJson(book).put("saved_at", System.currentTimeMillis()).toString())
            true
        }
        preferences.edit().putStringSet(FAVORITES_KEY, favorites).apply()
        return isFavorite
    }

    fun isFavorite(context: Context, book: Book): Boolean =
        getFavorites(context).any { bookKey(it) == bookKey(book) }

    fun getFavorites(context: Context): List<Book> =
        getPreferences(context)
            .getStringSet(FAVORITES_KEY, emptySet())
            .orEmpty()
            .map { json -> jsonToBook(JSONObject(json)) }
            .distinctBy(::bookKey)

    fun getFavoriteSavedDate(context: Context, book: Book): String {
        val preferences = getPreferences(context)
        val favorites = preferences.getStringSet(FAVORITES_KEY, emptySet()).orEmpty().toMutableSet()
        val savedEntry = favorites.firstOrNull { favoriteKey(it) == bookKey(book) }
            ?: return "Disimpan di Favorit"
        val savedJson = JSONObject(savedEntry)
        var savedAt = savedJson.optLong("saved_at", 0L)
        if (savedAt <= 0L) {
            savedAt = System.currentTimeMillis()
            favorites.remove(savedEntry)
            favorites.add(savedJson.put("saved_at", savedAt).toString())
            preferences.edit().putStringSet(FAVORITES_KEY, favorites).apply()
        }
        val formatter = SimpleDateFormat("d MMMM yyyy", Locale.forLanguageTag("id-ID"))
        return "Disimpan ${formatter.format(Date(savedAt))}"
    }

    // ==========================================
    // 2. PENANDA HALAMAN (BOOKMARK)
    // ==========================================

    fun togglePageBookmark(context: Context, book: Book, page: Int): Boolean {
        require(page > 0) { "Nomor halaman harus lebih besar dari nol." }
        val preferences = getPreferences(context)
        val bookmarks = getBookmarks(context).toMutableList()
        val index = bookmarks.indexOfFirst { bookKey(it.book) == bookKey(book) }
        val isBookmarked = if (index >= 0 && bookmarks[index].page == page) {
            bookmarks.removeAt(index)
            false
        } else {
            if (index >= 0) bookmarks.removeAt(index)
            bookmarks.add(SavedPageBookmark(book, page))
            true
        }
        preferences.edit().putStringSet(
            BOOKMARKS_KEY,
            bookmarks.map { bookmark ->
                JSONObject()
                    .put("page", bookmark.page)
                    .put("book", bookToJson(bookmark.book))
                    .toString()
            }.toSet()
        ).apply()
        return isBookmarked
    }

    fun setPageBookmark(context: Context, book: Book, page: Int) {
        require(page > 0) { "Nomor halaman harus lebih besar dari nol." }
        val preferences = getPreferences(context)
        val bookmarks = getBookmarks(context).toMutableList()
        val index = bookmarks.indexOfFirst { bookKey(it.book) == bookKey(book) }
        if (index >= 0) {
            bookmarks.removeAt(index)
        }
        bookmarks.add(SavedPageBookmark(book, page))
        preferences.edit().putStringSet(
            BOOKMARKS_KEY,
            bookmarks.map { bookmark ->
                JSONObject()
                    .put("page", bookmark.page)
                    .put("book", bookToJson(bookmark.book))
                    .toString()
            }.toSet()
        ).apply()
    }

    fun getPageBookmark(context: Context, book: Book): SavedPageBookmark? =
        getBookmarks(context).firstOrNull { bookKey(it.book) == bookKey(book) }

    fun getBookmarks(context: Context): List<SavedPageBookmark> =
        getPreferences(context)
            .getStringSet(BOOKMARKS_KEY, emptySet())
            .orEmpty()
            .mapNotNull { json ->
                runCatching {
                    val bookmark = JSONObject(json)
                    val page = bookmark.optInt("page")
                    if (page > 0) SavedPageBookmark(jsonToBook(bookmark.getJSONObject("book")), page) else null
                }.getOrNull()
            }

    // ==========================================
    // 3. SESI BACA AKTIF (RAK BUKU)
    // ==========================================

    fun getActiveSessions(context: Context): List<ActiveReadingSession> {
        val raw = getPreferences(context).getString(ACTIVE_SESSIONS_KEY, null) ?: return emptyList()
        return runCatching {
            val array = JSONArray(raw)
            val list = mutableListOf<ActiveReadingSession>()
            for (i in 0 until array.length()) {
                val obj = array.getJSONObject(i)
                val book = jsonToBook(obj.getJSONObject("book"))
                val curPage = obj.optInt("current_page", 1)
                val totPages = obj.optInt("total_pages", 100)
                val prog = obj.optInt("progress_percent", 0)
                val lastRead = obj.optLong("last_read_at", System.currentTimeMillis())
                list.add(ActiveReadingSession(book, curPage, totPages, prog, lastRead))
            }
            list.sortedByDescending { it.lastReadAt }
        }.getOrDefault(emptyList())
    }

    fun recordReadingSession(
        context: Context,
        book: Book,
        page: Int,
        totalPages: Int,
        progressPercent: Int? = null
    ) {
        val sessions = getActiveSessions(context).toMutableList()
        val calculatedProgress = progressPercent ?: if (totalPages > 0) {
            ((page.toFloat() / totalPages.toFloat()) * 100).toInt().coerceIn(0, 100)
        } else 0

        val index = sessions.indexOfFirst { bookKey(it.book) == bookKey(book) }
        val updated = ActiveReadingSession(
            book = book,
            currentPage = page.coerceAtLeast(1),
            totalPages = totalPages.coerceAtLeast(1),
            progressPercent = calculatedProgress,
            lastReadAt = System.currentTimeMillis()
        )

        if (index >= 0) {
            sessions[index] = updated
        } else {
            sessions.add(0, updated)
        }

        saveActiveSessionsList(context, sessions)

        // Jika progres membaca sudah 100%, tandai juga ke buku selesai
        if (calculatedProgress >= 100) {
            recordFinishedBook(context, book)
        }
    }

    fun removeActiveSession(context: Context, book: Book) {
        val sessions = getActiveSessions(context).filterNot { bookKey(it.book) == bookKey(book) }
        saveActiveSessionsList(context, sessions)
    }

    private fun saveActiveSessionsList(context: Context, sessions: List<ActiveReadingSession>) {
        val array = JSONArray()
        sessions.forEach { s ->
            val obj = JSONObject().apply {
                put("book", bookToJson(s.book))
                put("current_page", s.currentPage)
                put("total_pages", s.totalPages)
                put("progress_percent", s.progressPercent)
                put("last_read_at", s.lastReadAt)
            }
            array.put(obj)
        }
        getPreferences(context).edit().putString(ACTIVE_SESSIONS_KEY, array.toString()).apply()
    }

    // ==========================================
    // 4. RIWAYAT BUKU SELESAI DIBACA
    // ==========================================

    fun getFinishedBooks(context: Context): List<FinishedBook> {
        val raw = getPreferences(context).getString(FINISHED_BOOKS_KEY, null) ?: return emptyList()
        return runCatching {
            val array = JSONArray(raw)
            val list = mutableListOf<FinishedBook>()
            for (i in 0 until array.length()) {
                val obj = array.getJSONObject(i)
                val book = jsonToBook(obj.getJSONObject("book"))
                val finishedAt = obj.optLong("finished_at", System.currentTimeMillis())
                list.add(FinishedBook(book, finishedAt))
            }
            list.sortedByDescending { it.finishedAt }
        }.getOrDefault(emptyList())
    }

    fun recordFinishedBook(context: Context, book: Book) {
        val finished = getFinishedBooks(context).toMutableList()
        val index = finished.indexOfFirst { bookKey(it.book) == bookKey(book) }
        val item = FinishedBook(book, System.currentTimeMillis())
        if (index >= 0) {
            finished[index] = item
        } else {
            finished.add(0, item)
        }
        val array = JSONArray()
        finished.forEach { f ->
            val obj = JSONObject().apply {
                put("book", bookToJson(f.book))
                put("finished_at", f.finishedAt)
            }
            array.put(obj)
        }
        getPreferences(context).edit().putString(FINISHED_BOOKS_KEY, array.toString()).apply()
    }

    // ==========================================
    // 5. ULASAN BUKU
    // ==========================================

    fun saveReview(context: Context, book: Book, rating: Int, text: String) {
        getPreferences(context).edit()
            .putInt(reviewRatingKey(book), rating)
            .putString(reviewTextKey(book), text)
            .apply()
    }

    fun getReview(context: Context, book: Book): SavedBookReview? {
        val preferences = getPreferences(context)
        val text = preferences.getString(reviewTextKey(book), null) ?: return null
        val rating = preferences.getInt(reviewRatingKey(book), 0)
        return if (rating in 1..5) SavedBookReview(rating, text) else null
    }

    // ==========================================
    // 6. SOROTAN & CATATAN TEKS NASKAH
    // ==========================================

    fun saveHighlightNote(context: Context, highlight: SavedHighlightNote) {
        val prefs = getPreferences(context)
        val list = getAllHighlightNotes(context).toMutableList()
        val index = list.indexOfFirst { it.id == highlight.id }
        if (index >= 0) {
            list[index] = highlight
        } else {
            list.add(0, highlight)
        }
        val array = JSONArray()
        list.forEach { h ->
            array.put(JSONObject().apply {
                put("id", h.id)
                put("book_key", h.bookKey)
                put("page", h.page)
                put("left", h.rectLeftRatio.toDouble())
                put("top", h.rectTopRatio.toDouble())
                put("right", h.rectRightRatio.toDouble())
                put("bottom", h.rectBottomRatio.toDouble())
                put("color", h.colorHex)
                put("note", h.noteText)
                put("selected_text", h.selectedText)
                put("line_rects", h.lineRectsJson)
                put("created_at", h.createdAt)
            })
        }
        prefs.edit().putString(HIGHLIGHTS_KEY, array.toString()).apply()
    }

    fun getAllHighlightNotes(context: Context): List<SavedHighlightNote> {
        val raw = getPreferences(context).getString(HIGHLIGHTS_KEY, null) ?: return emptyList()
        return runCatching {
            val array = JSONArray(raw)
            val list = mutableListOf<SavedHighlightNote>()
            for (i in 0 until array.length()) {
                val obj = array.getJSONObject(i)
                list.add(
                    SavedHighlightNote(
                        id = obj.getString("id"),
                        bookKey = obj.getString("book_key"),
                        page = obj.optInt("page", 1),
                        rectLeftRatio = obj.optDouble("left", 0.0).toFloat(),
                        rectTopRatio = obj.optDouble("top", 0.0).toFloat(),
                        rectRightRatio = obj.optDouble("right", 1.0).toFloat(),
                        rectBottomRatio = obj.optDouble("bottom", 0.1).toFloat(),
                        colorHex = obj.optString("color", "#F8E79F"),
                        noteText = obj.optString("note", ""),
                        selectedText = obj.optString("selected_text", ""),
                        lineRectsJson = obj.optString("line_rects", ""),
                        createdAt = obj.optLong("created_at", System.currentTimeMillis())
                    )
                )
            }
            list
        }.getOrDefault(emptyList())
    }

    fun getHighlightsForPage(context: Context, book: Book, page: Int): List<SavedHighlightNote> {
        val key = bookKey(book)
        return getAllHighlightNotes(context).filter { it.bookKey == key && it.page == page }
    }

    fun getHighlightsForBook(context: Context, book: Book): List<SavedHighlightNote> {
        val key = bookKey(book)
        return getAllHighlightNotes(context).filter { it.bookKey == key }.sortedBy { it.page }
    }

    fun deleteHighlightNote(context: Context, id: String) {
        val prefs = getPreferences(context)
        val list = getAllHighlightNotes(context).filterNot { it.id == id }
        val array = JSONArray()
        list.forEach { h ->
            array.put(JSONObject().apply {
                put("id", h.id)
                put("book_key", h.bookKey)
                put("page", h.page)
                put("left", h.rectLeftRatio.toDouble())
                put("top", h.rectTopRatio.toDouble())
                put("right", h.rectRightRatio.toDouble())
                put("bottom", h.rectBottomRatio.toDouble())
                put("color", h.colorHex)
                put("note", h.noteText)
                put("created_at", h.createdAt)
            })
        }
        prefs.edit().putString(HIGHLIGHTS_KEY, array.toString()).apply()
    }

    private fun reviewRatingKey(book: Book) = "review:${bookKey(book)}:rating"
    private fun reviewTextKey(book: Book) = "review:${bookKey(book)}:text"

    private fun favoriteKey(json: String): String = bookKey(jsonToBook(JSONObject(json)))

    private fun bookToJson(book: Book) = JSONObject().apply {
        put("id", book.id)
        put("type", book.type)
        put("title", book.title)
        put("subtitle", book.subtitle)
        put("author", book.author)
        put("editor", book.editor)
        put("publisher", book.publisher)
        put("category", book.category)
        put("isbn", book.isbn)
        put("year", book.year)
        put("pages", book.pages)
        put("size", book.size)
        put("synopsis", book.synopsis)
        put("cover_url", book.cover_url)
        put("rating", book.rating)
        put("rating_count", book.rating_count)
        put("price", book.price)
        put("formatted_price", book.formatted_price)
        put("is_free", book.is_free)
        put("is_read_ready", book.is_read_ready)
        put("download_url", book.download_url)
    }

    private fun jsonToBook(json: JSONObject) = Book(
        id = json.optInt("id", -1),
        type = json.optString("type").takeUnless { it == "null" },
        title = json.optString("title"),
        subtitle = json.optString("subtitle").takeUnless { it == "null" },
        author = json.optString("author"),
        category = json.optString("category").takeUnless { it == "null" },
        editor = json.optString("editor").takeUnless { it == "null" },
        publisher = json.optString("publisher").takeUnless { it == "null" },
        isbn = json.optString("isbn").takeUnless { it == "null" },
        year = json.optString("year").takeUnless { it == "null" },
        pages = json.optString("pages").takeUnless { it == "null" },
        size = json.optString("size").takeUnless { it == "null" },
        synopsis = json.optString("synopsis").takeUnless { it == "null" },
        cover_url = json.optString("cover_url").takeUnless { it == "null" },
        rating = json.optDouble("rating", 4.9),
        rating_count = json.optInt("rating_count", 120),
        price = json.optDouble("price", 0.0),
        formatted_price = json.optString("formatted_price").takeUnless { it == "null" },
        is_free = json.optBoolean("is_free", true),
        is_read_ready = json.optBoolean("is_read_ready", true),
        download_url = json.optString("download_url").takeUnless { it == "null" }
    )
}
