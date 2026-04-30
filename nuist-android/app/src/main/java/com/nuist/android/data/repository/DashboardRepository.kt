package com.nuist.android.data.repository

import com.nuist.android.core.network.ApiResult
import com.nuist.android.core.network.safeApiCall
import com.nuist.android.data.remote.api.MobileApiService
import com.nuist.android.data.remote.dto.DashboardDto
import javax.inject.Inject
import javax.inject.Singleton

@Singleton
class DashboardRepository @Inject constructor(
    private val apiService: MobileApiService,
) {
    suspend fun getDashboard(): ApiResult<DashboardDto> {
        return when (val result = safeApiCall { apiService.dashboard() }) {
            is ApiResult.Success -> {
                val payload = result.data.data
                if (payload == null) {
                    ApiResult.Error("Data dashboard belum tersedia.")
                } else {
                    ApiResult.Success(payload)
                }
            }

            is ApiResult.Error -> result
            ApiResult.Unauthorized -> result
        }
    }
}
