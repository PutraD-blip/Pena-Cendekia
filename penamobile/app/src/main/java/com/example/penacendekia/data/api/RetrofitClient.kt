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

    @Volatile
    var currentHost: String = if (isEmulator) "10.0.2.2" else "192.168.100.190"

    const val PORT: Int = 8000

    val BASE_URL: String
        get() = "http://$currentHost:$PORT/api/"

    fun getFormattedImageUrl(url: String?): String? {
        val imageUrl = url?.trim()?.takeIf { it.isNotEmpty() } ?: return null
        val serverRoot = "http://$currentHost:$PORT"
        val localHostUrl = Regex("^https?://(?:127\\.0\\.0\\.1|localhost|10\\.0\\.2\\.2|172\\.14\\.5\\.135)(?::\\d+)?")
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

            // Try first with currentHost
            val primaryUrl = originalUrl.newBuilder()
                .host(currentHost)
                .port(PORT)
                .build()
            val primaryRequest = originalRequest.newBuilder().url(primaryUrl).build()

            try {
                val response = chain.proceed(primaryRequest)
                if (response.isSuccessful || response.code < 500) {
                    return response
                }
            } catch (e: IOException) {
                Log.w(TAG, "Failed connecting to host: $currentHost ($e). Attempting fallback host...")
            }

            // Fallback: switch between 10.0.2.2 (Emulator) and 172.14.5.135 (Wi-Fi)
            val fallbackHost = if (currentHost == "10.0.2.2") "172.14.5.135" else "10.0.2.2"
            val fallbackUrl = originalUrl.newBuilder()
                .host(fallbackHost)
                .port(PORT)
                .build()
            val fallbackRequest = originalRequest.newBuilder().url(fallbackUrl).build()

            return try {
                val response = chain.proceed(fallbackRequest)
                Log.i(TAG, "Fallback succeeded to host: $fallbackHost. Switching active host.")
                currentHost = fallbackHost
                response
            } catch (e: IOException) {
                Log.e(TAG, "All host candidates failed (current: $currentHost, fallback: $fallbackHost): ${e.message}")
                throw e
            }
        }
    }

    private val loggingInterceptor = HttpLoggingInterceptor().apply {
        level = HttpLoggingInterceptor.Level.BODY
    }

    private val okHttpClient = OkHttpClient.Builder()
        .addInterceptor(DynamicHostFallbackInterceptor())
        .addInterceptor(loggingInterceptor)
        .connectTimeout(10, TimeUnit.SECONDS)
        .readTimeout(10, TimeUnit.SECONDS)
        .writeTimeout(10, TimeUnit.SECONDS)
        .build()

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
