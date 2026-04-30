package com.nuist.android.data.remote.dto

import com.google.gson.annotations.SerializedName

data class IzinListDto(
    val items: List<IzinItemDto> = emptyList(),
)

data class IzinDetailDto(
    val item: IzinItemDto,
)

data class IzinItemDto(
    val id: Long,
    val type: String,
    val title: String,
    val status: String,
    val reason: String? = null,
    @SerializedName("submitted_at")
    val submittedAt: String? = null,
)
