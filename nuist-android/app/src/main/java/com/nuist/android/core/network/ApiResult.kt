package com.nuist.android.core.network

import java.io.IOException
import retrofit2.HttpException

sealed interface ApiResult<out T> {
    data class Success<T>(val data: T) : ApiResult<T>
    data class Error(val message: String, val code: Int? = null) : ApiResult<Nothing>
    data object Unauthorized : ApiResult<Nothing>
}

suspend fun <T> safeApiCall(block: suspend () -> T): ApiResult<T> {
    return try {
        ApiResult.Success(block())
    } catch (exception: HttpException) {
        if (exception.code() == 401) {
            ApiResult.Unauthorized
        } else {
            ApiResult.Error(
                message = exception.message ?: "Terjadi kesalahan server.",
                code = exception.code(),
            )
        }
    } catch (_: IOException) {
        ApiResult.Error("Tidak dapat terhubung ke server.")
    } catch (exception: Exception) {
        ApiResult.Error(exception.message ?: "Terjadi kesalahan tidak dikenal.")
    }
}
