package com.nuist.android.data.repository

import com.nuist.android.core.network.ApiResult
import com.nuist.android.core.network.safeApiCall
import com.nuist.android.data.remote.api.MobileApiService
import com.nuist.android.data.remote.dto.BillingListDto
import javax.inject.Inject
import javax.inject.Singleton

@Singleton
class BillingRepository @Inject constructor(
    private val apiService: MobileApiService,
) {
    suspend fun getBills(): ApiResult<BillingListDto> {
        return when (val result = safeApiCall { apiService.bills() }) {
            is ApiResult.Success -> {
                val payload = result.data.data
                if (payload == null) {
                    ApiResult.Error("Data tagihan belum tersedia.")
                } else {
                    ApiResult.Success(payload)
                }
            }

            is ApiResult.Error -> result
            ApiResult.Unauthorized -> result
        }
    }
}
