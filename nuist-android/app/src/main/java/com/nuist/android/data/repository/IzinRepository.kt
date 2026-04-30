package com.nuist.android.data.repository

import com.nuist.android.core.network.ApiResult
import com.nuist.android.core.network.safeApiCall
import com.nuist.android.data.remote.api.MobileApiService
import com.nuist.android.data.remote.dto.IzinDetailDto
import com.nuist.android.data.remote.dto.IzinListDto
import javax.inject.Inject
import javax.inject.Singleton

@Singleton
class IzinRepository @Inject constructor(
    private val apiService: MobileApiService,
) {
    suspend fun getIzinList(): ApiResult<IzinListDto> {
        return when (val result = safeApiCall { apiService.izinList() }) {
            is ApiResult.Success -> {
                val payload = result.data.data
                if (payload == null) {
                    ApiResult.Error("Data izin belum tersedia.")
                } else {
                    ApiResult.Success(payload)
                }
            }

            is ApiResult.Error -> result
            ApiResult.Unauthorized -> result
        }
    }

    suspend fun getIzinDetail(id: Long): ApiResult<IzinDetailDto> {
        return when (val result = safeApiCall { apiService.izinDetail(id) }) {
            is ApiResult.Success -> {
                val payload = result.data.data
                if (payload == null) {
                    ApiResult.Error("Detail izin belum tersedia.")
                } else {
                    ApiResult.Success(payload)
                }
            }

            is ApiResult.Error -> result
            ApiResult.Unauthorized -> result
        }
    }
}
