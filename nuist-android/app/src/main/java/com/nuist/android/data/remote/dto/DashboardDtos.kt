package com.nuist.android.data.remote.dto

import com.google.gson.annotations.SerializedName

data class DashboardDto(
    val greeting: String? = null,
    val summary: DashboardSummaryDto = DashboardSummaryDto(),
    val shortcuts: List<DashboardShortcutDto> = emptyList(),
)

data class DashboardSummaryDto(
    @SerializedName("attendance_percent")
    val attendancePercent: Double = 0.0,
    @SerializedName("pending_izin_count")
    val pendingIzinCount: Int = 0,
    @SerializedName("unpaid_bill_count")
    val unpaidBillCount: Int = 0,
)

data class DashboardShortcutDto(
    val id: String,
    val title: String,
    val subtitle: String? = null,
)
