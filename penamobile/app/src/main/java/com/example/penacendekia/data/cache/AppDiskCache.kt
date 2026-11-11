package com.example.penacendekia.data.cache

import android.content.Context
import android.util.Log
import com.example.penacendekia.data.model.Book
import com.example.penacendekia.data.model.HomeData
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken

object AppDiskCache {
    private const val TAG = "AppDiskCache"
    private const val PREFS_CACHE = "penacendekia_app_cache_v6"
    private const val KEY_HOME_DATA = "cached_home_data_json"
    private const val KEY_CATALOG_BOOKS = "cached_catalog_books_json"

    private val gson by lazy { Gson() }

    fun saveHomeData(context: Context, homeData: HomeData) {
        try {
            val json = gson.toJson(homeData)
            context.getSharedPreferences(PREFS_CACHE, Context.MODE_PRIVATE)
                .edit()
                .putString(KEY_HOME_DATA, json)
                .apply()
        } catch (e: Exception) {
            Log.e(TAG, "Failed saving HomeData to disk cache: ${e.message}", e)
        }
    }

    fun getHomeData(context: Context): HomeData? {
        return try {
            val prefs = context.getSharedPreferences(PREFS_CACHE, Context.MODE_PRIVATE)
            val json = prefs.getString(KEY_HOME_DATA, null) ?: return null
            gson.fromJson(json, HomeData::class.java)
        } catch (e: Exception) {
            Log.e(TAG, "Failed reading HomeData from disk cache: ${e.message}", e)
            null
        }
    }

    fun saveCatalogBooks(context: Context, books: List<Book>) {
        try {
            val json = gson.toJson(books)
            context.getSharedPreferences(PREFS_CACHE, Context.MODE_PRIVATE)
                .edit()
                .putString(KEY_CATALOG_BOOKS, json)
                .apply()
        } catch (e: Exception) {
            Log.e(TAG, "Failed saving CatalogBooks to disk cache: ${e.message}", e)
        }
    }

    fun getCatalogBooks(context: Context): List<Book>? {
        return try {
            val prefs = context.getSharedPreferences(PREFS_CACHE, Context.MODE_PRIVATE)
            val json = prefs.getString(KEY_CATALOG_BOOKS, null) ?: return null
            val type = object : TypeToken<List<Book>>() {}.type
            gson.fromJson<List<Book>>(json, type)
        } catch (e: Exception) {
            Log.e(TAG, "Failed reading CatalogBooks from disk cache: ${e.message}", e)
            null
        }
    }

    fun clearCache(context: Context) {
        context.getSharedPreferences(PREFS_CACHE, Context.MODE_PRIVATE)
            .edit()
            .clear()
            .apply()
    }
}
