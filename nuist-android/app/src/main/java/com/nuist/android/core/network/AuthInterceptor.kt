package com.nuist.android.core.network

import com.nuist.android.core.data.local.TokenManager
import javax.inject.Inject
import javax.inject.Singleton
import kotlinx.coroutines.runBlocking
import okhttp3.Interceptor
import okhttp3.Response

@Singleton
class AuthInterceptor @Inject constructor(
    private val tokenManager: TokenManager,
) : Interceptor {
    override fun intercept(chain: Interceptor.Chain): Response {
        val originalRequest = chain.request()
        val token = runBlocking { tokenManager.getToken() }

        if (token.isNullOrBlank()) {
            return chain.proceed(originalRequest)
        }

        val requestWithToken = originalRequest.newBuilder()
            .addHeader("Authorization", "Bearer $token")
            .build()

        return chain.proceed(requestWithToken)
    }
}
