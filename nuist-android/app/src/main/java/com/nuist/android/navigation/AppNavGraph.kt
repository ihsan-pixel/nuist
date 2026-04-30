package com.nuist.android.navigation

import androidx.compose.foundation.layout.padding
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.outlined.AccountCircle
import androidx.compose.material.icons.outlined.Home
import androidx.compose.material.icons.outlined.List
import androidx.compose.material.icons.outlined.ReceiptLong
import androidx.compose.material3.Icon
import androidx.compose.material3.NavigationBar
import androidx.compose.material3.NavigationBarItem
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.ui.Modifier
import androidx.hilt.navigation.compose.hiltViewModel
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import androidx.navigation.NavGraph.Companion.findStartDestination
import androidx.navigation.NavType
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.currentBackStackEntryAsState
import androidx.navigation.compose.rememberNavController
import androidx.navigation.navArgument
import com.nuist.android.MainViewModel
import com.nuist.android.SessionState
import com.nuist.android.core.ui.components.LoadingState
import com.nuist.android.feature.auth.LoginRoute
import com.nuist.android.feature.billing.BillingRoute
import com.nuist.android.feature.dashboard.DashboardRoute
import com.nuist.android.feature.izin.IzinDetailRoute
import com.nuist.android.feature.izin.IzinRoute
import com.nuist.android.feature.profile.ProfileRoute

private data class BottomNavItem(
    val route: String,
    val label: String,
    val icon: androidx.compose.ui.graphics.vector.ImageVector,
)

@Composable
fun AppNavGraph(
    modifier: Modifier = Modifier,
    viewModel: MainViewModel = hiltViewModel(),
) {
    val navController = rememberNavController()
    val sessionState by viewModel.sessionState.collectAsStateWithLifecycle()

    if (sessionState == SessionState.CHECKING) {
        LoadingState()
        return
    }

    val startDestination = when (sessionState) {
        SessionState.AUTHENTICATED -> AppDestination.Dashboard.route
        SessionState.UNAUTHENTICATED -> AppDestination.Login.route
        SessionState.CHECKING -> AppDestination.Login.route
    }

    val backStackEntry by navController.currentBackStackEntryAsState()
    val currentRoute = backStackEntry?.destination?.route

    val bottomItems = listOf(
        BottomNavItem(AppDestination.Dashboard.route, "Beranda", Icons.Outlined.Home),
        BottomNavItem(AppDestination.Billing.route, "Tagihan", Icons.Outlined.ReceiptLong),
        BottomNavItem(AppDestination.Izin.route, "Izin", Icons.Outlined.List),
        BottomNavItem(AppDestination.Profile.route, "Profil", Icons.Outlined.AccountCircle),
    )

    val showBottomBar = currentRoute in bottomItems.map { it.route }

    Scaffold(
        modifier = modifier,
        bottomBar = {
            if (showBottomBar) {
                NavigationBar {
                    bottomItems.forEach { item ->
                        val selected = currentRoute == item.route
                        NavigationBarItem(
                            selected = selected,
                            onClick = {
                                navController.navigate(item.route) {
                                    popUpTo(navController.graph.findStartDestination().id) {
                                        saveState = true
                                    }
                                    launchSingleTop = true
                                    restoreState = true
                                }
                            },
                            icon = {
                                Icon(
                                    imageVector = item.icon,
                                    contentDescription = item.label,
                                )
                            },
                            label = { Text(item.label) },
                        )
                    }
                }
            }
        },
    ) { innerPadding ->
        NavHost(
            navController = navController,
            startDestination = startDestination,
            modifier = Modifier.padding(innerPadding),
        ) {
            composable(AppDestination.Login.route) {
                LoginRoute(
                    onLoginSuccess = {
                        navController.navigate(AppDestination.Dashboard.route) {
                            popUpTo(AppDestination.Login.route) { inclusive = true }
                        }
                    },
                )
            }

            composable(AppDestination.Dashboard.route) {
                DashboardRoute(
                    onOpenBilling = { navController.navigate(AppDestination.Billing.route) },
                    onOpenIzin = { navController.navigate(AppDestination.Izin.route) },
                    onUnauthorized = {
                        navController.navigate(AppDestination.Login.route) {
                            popUpTo(navController.graph.id) { inclusive = true }
                        }
                    },
                )
            }

            composable(AppDestination.Billing.route) {
                BillingRoute(
                    onUnauthorized = {
                        navController.navigate(AppDestination.Login.route) {
                            popUpTo(navController.graph.id) { inclusive = true }
                        }
                    },
                )
            }

            composable(AppDestination.Izin.route) {
                IzinRoute(
                    onOpenDetail = { izinId ->
                        navController.navigate(AppDestination.IzinDetail.createRoute(izinId))
                    },
                    onUnauthorized = {
                        navController.navigate(AppDestination.Login.route) {
                            popUpTo(navController.graph.id) { inclusive = true }
                        }
                    },
                )
            }

            composable(
                route = AppDestination.IzinDetail.route,
                arguments = listOf(navArgument("izinId") { type = NavType.StringType }),
            ) {
                IzinDetailRoute(
                    onBack = { navController.popBackStack() },
                    onUnauthorized = {
                        navController.navigate(AppDestination.Login.route) {
                            popUpTo(navController.graph.id) { inclusive = true }
                        }
                    },
                )
            }

            composable(AppDestination.Profile.route) {
                ProfileRoute(
                    onLoggedOut = {
                        navController.navigate(AppDestination.Login.route) {
                            popUpTo(navController.graph.id) { inclusive = true }
                        }
                    },
                )
            }
        }
    }
}
