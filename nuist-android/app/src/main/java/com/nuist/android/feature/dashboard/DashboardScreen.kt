package com.nuist.android.feature.dashboard

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.items
import androidx.compose.material3.Button
import androidx.compose.material3.Card
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.getValue
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.hilt.navigation.compose.hiltViewModel
import androidx.lifecycle.compose.collectAsStateWithLifecycle
import com.nuist.android.core.ui.components.ErrorState
import com.nuist.android.core.ui.components.InfoCard
import com.nuist.android.core.ui.components.LoadingState
import com.nuist.android.core.ui.components.SectionList
import com.nuist.android.core.ui.state.UiState
import com.nuist.android.data.remote.dto.DashboardDto

@Composable
fun DashboardRoute(
    onOpenBilling: () -> Unit,
    onOpenIzin: () -> Unit,
    onUnauthorized: () -> Unit,
    viewModel: DashboardViewModel = hiltViewModel(),
) {
    val state by viewModel.uiState.collectAsStateWithLifecycle()
    val expired by viewModel.sessionExpired.collectAsStateWithLifecycle()

    when (state) {
        UiState.Loading -> LoadingState()
        is UiState.Error -> ErrorState(
            message = (state as UiState.Error).message,
            onRetry = viewModel::loadData,
        )

        UiState.Empty -> ErrorState(
            message = "Dashboard belum memiliki data.",
            onRetry = viewModel::loadData,
        )

        is UiState.Success -> DashboardScreen(
            data = (state as UiState.Success<DashboardDto>).data,
            onOpenBilling = onOpenBilling,
            onOpenIzin = onOpenIzin,
        )
    }

    LaunchedEffect(expired) {
        if (expired) {
            onUnauthorized()
        }
    }
}

@Composable
private fun DashboardScreen(
    data: DashboardDto,
    onOpenBilling: () -> Unit,
    onOpenIzin: () -> Unit,
) {
    SectionList {
        item {
            InfoCard(
                title = "Kehadiran bulan ini",
                value = "${data.summary.attendancePercent}%",
                helper = data.greeting ?: "Selamat datang di aplikasi Nuist Mobile",
            )
        }

        item {
            Column(verticalArrangement = Arrangement.spacedBy(12.dp)) {
                Button(
                    onClick = onOpenBilling,
                    modifier = Modifier.fillMaxWidth(),
                ) {
                    Text("Buka Tagihan")
                }
                Button(
                    onClick = onOpenIzin,
                    modifier = Modifier.fillMaxWidth(),
                ) {
                    Text("Buka Izin")
                }
            }
        }

        item {
            Card {
                Column(modifier = Modifier.padding(16.dp)) {
                    Text(
                        text = "Ringkasan",
                        style = MaterialTheme.typography.titleMedium,
                        fontWeight = FontWeight.Bold,
                    )
                    Text(
                        text = "Izin tertunda: ${data.summary.pendingIzinCount}",
                        modifier = Modifier.padding(top = 8.dp),
                    )
                    Text(
                        text = "Tagihan belum lunas: ${data.summary.unpaidBillCount}",
                        modifier = Modifier.padding(top = 4.dp),
                    )
                }
            }
        }

        if (data.shortcuts.isNotEmpty()) {
            item {
                Text(
                    text = "Shortcut",
                    style = MaterialTheme.typography.titleMedium,
                    fontWeight = FontWeight.Bold,
                )
            }
            items(data.shortcuts, key = { it.id }) { shortcut ->
                Card {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Text(
                            text = shortcut.title,
                            style = MaterialTheme.typography.titleSmall,
                            fontWeight = FontWeight.SemiBold,
                        )
                        if (!shortcut.subtitle.isNullOrBlank()) {
                            Text(
                                text = shortcut.subtitle,
                                modifier = Modifier.padding(top = 4.dp),
                                color = MaterialTheme.colorScheme.onSurfaceVariant,
                            )
                        }
                    }
                }
            }
        }
    }
}
