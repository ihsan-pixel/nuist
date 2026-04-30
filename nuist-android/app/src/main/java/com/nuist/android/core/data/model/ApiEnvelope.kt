package com.nuist.android.core.data.model

data class ApiEnvelope<T>(
    val message: String? = null,
    val data: T? = null,
)
