package com.nuist.android.navigation

sealed class AppDestination(val route: String) {
    data object Login : AppDestination("login")
    data object Dashboard : AppDestination("dashboard")
    data object Billing : AppDestination("billing")
    data object Izin : AppDestination("izin")
    data object Profile : AppDestination("profile")
    data object IzinDetail : AppDestination("izin/{izinId}") {
        fun createRoute(izinId: Long): String = "izin/$izinId"
    }
}
