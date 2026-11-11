package com.example.penacendekia.data.api

import android.os.Build
import android.util.Log
import okhttp3.Interceptor
import okhttp3.OkHttpClient
import okhttp3.Response
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory
import java.io.IOException
import java.util.concurrent.TimeUnit

object RetrofitClient {
    private const val TAG = "RetrofitClient"

    val isEmulator: Boolean
        get() = (Build.FINGERPRINT.startsWith("generic")
                || Build.FINGERPRINT.startsWith("unknown")
                || Build.MODEL.contains("google_sdk")
                || Build.MODEL.contains("Emulator")
                || Build.MODEL.contains("Android SDK built for x86")
                || Build.MODEL.contains("sdk_gphone")
                || Build.PRODUCT.contains("sdk_gphone")
                || Build.PRODUCT.contains("google_sdk")
                || Build.HARDWARE.contains("goldfish")
                || Build.HARDWARE.contains("ranchu")
                || Build.MANUFACTURER.contains("Genymotion")
                || (Build.BRAND.startsWith("generic") && Build.DEVICE.startsWith("generic")))

    /**
     * Membersihkan string host dari spasi yang tidak sengaja terketik, awalan http/https, port, atau slash
     * agar tidak pernah menyebabkan crash unexpected host (IllegalArgumentException).
     */
    fun sanitizeHost(raw: String?): String {
        if (raw.isNullOrBlank()) return if (isEmulator) "10.0.2.2" else "192.168.68.114"
        return raw.trim()
            .removePrefix("http://")
            .removePrefix("https://")
            .substringBefore(":")
            .substringBefore("/")
            .trim()
    }

    @Volatile
    var currentHost: String = sanitizeHost(if (isEmulator) "10.0.2.2" else "192.168.68.114")
        set(value) {
            field = sanitizeHost(value)
        }

    const val PORT: Int = 8000

    val BASE_URL: String
        get() = "http://${sanitizeHost(currentHost)}:$PORT/api/"

    fun getFormattedImageUrl(url: String?): String? {
        val imageUrl = url?.trim()?.takeIf { it.isNotEmpty() } ?: return null
        val safeHost = sanitizeHost(currentHost)
        val serverRoot = "http://$safeHost:$PORT"
        val localHostUrl = Regex("^https?://(?:127\\.0\\.0\\.1|localhost|10\\.0\\.2\\.2|172\\.14\\.\\d+\\.\\d+|192\\.168\\.\\d+\\.\\d+)(?::\\d+)?")
            .replace(imageUrl, serverRoot)
        return if (localHostUrl.startsWith("http://") || localHostUrl.startsWith("https://")) {
            localHostUrl
        } else {
            "$serverRoot/${localHostUrl.trimStart('/')}"
        }
    }

    private class DynamicHostFallbackInterceptor : Interceptor {
        override fun intercept(chain: Interceptor.Chain): Response {
            val originalRequest = chain.request()
            val originalUrl = originalRequest.url
            val safeHost = sanitizeHost(currentHost)

            // 1. Try first with currentHost
            val primaryUrl = try {
                originalUrl.newBuilder()
                    .host(safeHost)
                    .port(PORT)
                    .build()
            } catch (e: Exception) {
                Log.e(TAG, "Invalid primary URL host: $safeHost", e)
                originalUrl
            }
            val primaryRequest = originalRequest.newBuilder().url(primaryUrl).build()

            try {
                val response = chain.proceed(primaryRequest)
                if (response.isSuccessful || response.code < 500) {
                    return response
                }
            } catch (e: IOException) {
                Log.w(TAG, "Failed connecting to host: $safeHost ($e). Attempting fallback hosts...")
            }

            // 2. Fallback candidates
            val candidates = listOf("192.168.68.114", "192.168.100.190", "192.168.68.146", "10.0.2.2")
                .filter { it != safeHost }

            for (fallbackHost in candidates) {
                val fallbackUrl = try {
                    originalUrl.newBuilder()
                        .host(fallbackHost)
                        .port(PORT)
                        .build()
                } catch (e: Exception) {
                    continue
                }
                val fallbackRequest = originalRequest.newBuilder().url(fallbackUrl).build()
                try {
                    val response = chain.proceed(fallbackRequest)
                    if (response.isSuccessful || response.code < 500) {
                        Log.i(TAG, "Fallback succeeded to host: $fallbackHost. Switching active host.")
                        currentHost = fallbackHost
                        return response
                    }
                } catch (e: IOException) {
                    // try next candidate
                }
            }

            throw IOException("All host candidates failed (current: $safeHost)")
        }
    }

    private val loggingInterceptor = HttpLoggingInterceptor().apply {
        level = HttpLoggingInterceptor.Level.BODY
    }

    private val okHttpClient = OkHttpClient.Builder()
        .addInterceptor(DynamicHostFallbackInterceptor())
        .addInterceptor(loggingInterceptor)
        .connectTimeout(15, TimeUnit.SECONDS)
        .readTimeout(30, TimeUnit.SECONDS)
        .writeTimeout(30, TimeUnit.SECONDS)
        .build()

    val httpClient: OkHttpClient
        get() = okHttpClient

    val instance: Retrofit by lazy {
        Retrofit.Builder()
            .baseUrl("http://10.0.2.2:8000/api/")
            .client(okHttpClient)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
    }

    val apiService: ApiService by lazy {
        instance.create(ApiService::class.java)
    }
}
