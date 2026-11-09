package com.example.penacendekia

import android.content.Context
import com.example.penacendekia.data.model.Book
import org.json.JSONObject
import java.text.SimpleDateFormat
import java.util.Date
import java.util.Locale

data class SavedBookReview(val rating: Int, val text: String)
data class SavedPageBookmark(val book: Book, val page: Int)

object BookActivityStore {
    private const val PREFS_NAME = "book_activity_store"
    private const val FAVORITES_KEY = "favorites"
    private const val BOOKMARKS_KEY = "page_bookmarks"

    fun bookKey(book: Book): String {
        val normalizedTitle = book.title.trim().lowercase().replace(Regex("\\s+"), " ")
        return if (normalizedTitle.isNotEmpty()) "title:$normalizedTitle" else "id:${book.id}"
    }

    fun toggleFavorite(context: Context, book: Book): Boolean {
        val preferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
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
        context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
            .getStringSet(FAVORITES_KEY, emptySet())
            .orEmpty()
            .map { json -> jsonToBook(JSONObject(json)) }
            .distinctBy(::bookKey)

    fun getFavoriteSavedDate(context: Context, book: Book): String {
        val preferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
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

    fun togglePageBookmark(context: Context, book: Book, page: Int): Boolean {
        require(page > 0) { "Nomor halaman harus lebih besar dari nol." }
        val preferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
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

    fun getPageBookmark(context: Context, book: Book): SavedPageBookmark? =
        getBookmarks(context).firstOrNull { bookKey(it.book) == bookKey(book) }

    fun getBookmarks(context: Context): List<SavedPageBookmark> =
        context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
            .getStringSet(BOOKMARKS_KEY, emptySet())
            .orEmpty()
            .mapNotNull { json ->
                runCatching {
                    val bookmark = JSONObject(json)
                    val page = bookmark.optInt("page")
                    if (page > 0) SavedPageBookmark(jsonToBook(bookmark.getJSONObject("book")), page) else null
                }.getOrNull()
            }

    fun saveReview(context: Context, book: Book, rating: Int, text: String) {
        context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE).edit()
            .putInt(reviewRatingKey(book), rating)
            .putString(reviewTextKey(book), text)
            .apply()
    }

    fun getReview(context: Context, book: Book): SavedBookReview? {
        val preferences = context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE)
        val text = preferences.getString(reviewTextKey(book), null) ?: return null
        val rating = preferences.getInt(reviewRatingKey(book), 0)
        return if (rating in 1..5) SavedBookReview(rating, text) else null
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
