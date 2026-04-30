package com.nuist.android.data.remote.dto

import com.google.gson.annotations.SerializedName

data class BillingListDto(
    val items: List<BillingItemDto> = emptyList(),
    @SerializedName("total_unpaid")
    val totalUnpaid: Long = 0,
)

data class BillingItemDto(
    val id: Long,
    @SerializedName("nomor_tagihan")
    val nomorTagihan: String,
    @SerializedName("jenis_tagihan")
    val jenisTagihan: String? = null,
    val periode: String,
    @SerializedName("jatuh_tempo")
    val jatuhTempo: String? = null,
    @SerializedName("total_tagihan")
    val totalTagihan: Long,
    val status: String,
)
