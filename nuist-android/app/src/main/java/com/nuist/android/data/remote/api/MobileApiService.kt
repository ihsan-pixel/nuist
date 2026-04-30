package com.nuist.android.data.remote.api

import com.nuist.android.core.data.model.ApiEnvelope
import com.nuist.android.data.remote.dto.BillingListDto
import com.nuist.android.data.remote.dto.DashboardDto
import com.nuist.android.data.remote.dto.IzinDetailDto
import com.nuist.android.data.remote.dto.IzinListDto
import com.nuist.android.data.remote.dto.LoginRequest
import com.nuist.android.data.remote.dto.LoginResponse
import com.nuist.android.data.remote.dto.MessageDto
import com.nuist.android.data.remote.dto.UserDto
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.POST
import retrofit2.http.Path

interface MobileApiService {

    @POST("api/mobile/login")
    suspend fun login(@Body request: LoginRequest): LoginResponse

    @POST("api/mobile/logout")
    suspend fun logout(): MessageDto

    @GET("api/mobile/me")
    suspend fun me(): ApiEnvelope<UserDto>

    @GET("api/mobile/dashboard")
    suspend fun dashboard(): ApiEnvelope<DashboardDto>

    @GET("api/mobile/tagihan")
    suspend fun bills(): ApiEnvelope<BillingListDto>

    @GET("api/mobile/izin")
    suspend fun izinList(): ApiEnvelope<IzinListDto>

    @GET("api/mobile/izin/{id}")
    suspend fun izinDetail(@Path("id") id: Long): ApiEnvelope<IzinDetailDto>
}
