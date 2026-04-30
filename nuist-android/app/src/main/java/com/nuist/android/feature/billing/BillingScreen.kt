package com.nuist.android.feature.billing

import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.items
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
import com.nuist.android.core.ui.components.EmptyState
import com.nuist.android.core.ui.components.ErrorState
import com.nuist.android.core.ui.components.InfoCard
import com.nuist.android.core.ui.components.LabeledValue
import com.nuist.android.core.ui.components.LoadingState
import com.nuist.android.core.ui.components.SectionList
import com.nuist.android.core.ui.state.UiState
import com.nuist.android.data.remote.dto.BillingListDto

@Composable
fun BillingRoute(
    onUnauthorized: () -> Unit,
    viewModel: BillingViewModel = hiltViewModel(),
) {
    val state by viewModel.uiState.collectAsStateWithLifecycle()

    when (state) {
        UiState.Loading -> LoadingState()
        is UiState.Error -> ErrorState(
            message = (state as UiState.Error).message,
            onRetry = viewModel::loadData,
        )

        UiState.Empty -> EmptyState(
            title = "Belum ada tagihan",
            subtitle = "Daftar tagihan siswa akan muncul di sini.",
        )

        is UiState.Success -> BillingScreen((state as UiState.Success<BillingListDto>).data)
    }

    val expired by viewModel.sessionExpired.collectAsStateWithLifecycle()
    LaunchedEffect(expired) {
        if (expired) {
            onUnauthorized()
        }
    }
}

@Composable
private fun BillingScreen(data: BillingListDto) {
    SectionList {
        item {
            InfoCard(
                title = "Total belum lunas",
                value = "Rp ${data.totalUnpaid}",
                helper = "${data.items.size} invoice tercatat",
            )
        }

        items(data.items, key = { it.id }) { item ->
            Card {
                Column(modifier = Modifier.padding(16.dp)) {
                    Text(
                        text = item.nomorTagihan,
                        style = MaterialTheme.typography.titleMedium,
                        fontWeight = FontWeight.Bold,
                    )
                    Text(
                        text = item.jenisTagihan ?: "Tagihan",
                        modifier = Modifier.padding(top = 4.dp),
                        color = MaterialTheme.colorScheme.onSurfaceVariant,
                    )
                    Column(modifier = Modifier.padding(top = 12.dp)) {
                        LabeledValue(label = "Periode", value = item.periode)
                        LabeledValue(label = "Jatuh Tempo", value = item.jatuhTempo ?: "-")
                        LabeledValue(label = "Status", value = item.status)
                        LabeledValue(label = "Total", value = "Rp ${item.totalTagihan}")
                    }
                }
            }
        }
    }
}
