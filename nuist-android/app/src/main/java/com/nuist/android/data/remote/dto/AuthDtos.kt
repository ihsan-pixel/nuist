package com.nuist.android.data.remote.dto

data class LoginRequest(
    val email: String,
    val password: String,
)

data class LoginResponse(
    val token: String,
    val user: UserDto,
    val mobile_route: String? = null,
)

data class UserDto(
    val id: Long,
    val name: String,
    val email: String,
    val role: String,
    val photo_url: String? = null,
    val nuist_id: String? = null,
)

data class MessageDto(
    val message: String? = null,
)
