package com.nuist.android.data.repository

import com.nuist.android.core.data.local.TokenManager
import com.nuist.android.core.network.ApiResult
import com.nuist.android.core.network.safeApiCall
import com.nuist.android.data.remote.api.MobileApiService
import com.nuist.android.data.remote.dto.LoginRequest
import com.nuist.android.data.remote.dto.LoginResponse
import com.nuist.android.data.remote.dto.UserDto
import javax.inject.Inject
import javax.inject.Singleton
import kotlinx.coroutines.flow.Flow

@Singleton
class AuthRepository @Inject constructor(
    private val apiService: MobileApiService,
    private val tokenManager: TokenManager,
) {
    val tokenFlow: Flow<String?> = tokenManager.tokenFlow

    suspend fun login(email: String, password: String): ApiResult<LoginResponse> {
        val result = safeApiCall {
            apiService.login(LoginRequest(email = email, password = password))
        }

        if (result is ApiResult.Success) {
            tokenManager.saveToken(result.data.token)
        }

        return result
    }

    suspend fun getMe(): ApiResult<UserDto> {
        return when (val result = safeApiCall { apiService.me() }) {
            is ApiResult.Success -> {
                val user = result.data.data
                if (user == null) {
                    ApiResult.Error("Data pengguna tidak tersedia.")
                } else {
                    ApiResult.Success(user)
                }
            }

            is ApiResult.Error -> result
            ApiResult.Unauthorized -> result
        }
    }

    suspend fun logout(): ApiResult<Unit> {
        val result = safeApiCall { apiService.logout() }
        tokenManager.clearSession()

        return when (result) {
            is ApiResult.Success -> ApiResult.Success(Unit)
            is ApiResult.Error -> result
            ApiResult.Unauthorized -> ApiResult.Success(Unit)
        }
    }

    suspend fun clearLocalSession() {
        tokenManager.clearSession()
    }
}
