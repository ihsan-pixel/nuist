package com.nuist.android.feature.izin

import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.lazy.items
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.filled.ArrowBack
import androidx.compose.material3.Card
import androidx.compose.material3.Icon
import androidx.compose.material3.IconButton
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBar
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
import com.nuist.android.core.ui.components.LabeledValue
import com.nuist.android.core.ui.components.LoadingState
import com.nuist.android.core.ui.components.SectionList
import com.nuist.android.core.ui.state.UiState
import com.nuist.android.data.remote.dto.IzinDetailDto
import com.nuist.android.data.remote.dto.IzinListDto

@Composable
fun IzinRoute(
    onOpenDetail: (Long) -> Unit,
    onUnauthorized: () -> Unit,
    viewModel: IzinViewModel = hiltViewModel(),
) {
    val state by viewModel.uiState.collectAsStateWithLifecycle()
    val expired by viewModel.sessionExpired.collectAsStateWithLifecycle()

    when (state) {
        UiState.Loading -> LoadingState()
        is UiState.Error -> ErrorState(
            message = (state as UiState.Error).message,
            onRetry = viewModel::loadData,
        )

        UiState.Empty -> EmptyState(
            title = "Belum ada izin",
            subtitle = "Riwayat izin akan ditampilkan di sini.",
        )

        is UiState.Success -> IzinScreen(
            data = (state as UiState.Success<IzinListDto>).data,
            onOpenDetail = onOpenDetail,
        )
    }

    LaunchedEffect(expired) {
        if (expired) {
            onUnauthorized()
        }
    }
}

@Composable
private fun IzinScreen(
    data: IzinListDto,
    onOpenDetail: (Long) -> Unit,
) {
    SectionList {
        items(data.items, key = { it.id }) { item ->
            Card(
                modifier = Modifier.clickable { onOpenDetail(item.id) },
            ) {
                Column(modifier = Modifier.padding(16.dp)) {
                    Text(
                        text = item.title,
                        style = MaterialTheme.typography.titleMedium,
                        fontWeight = FontWeight.Bold,
                    )
                    Text(
                        text = item.type,
                        modifier = Modifier.padding(top = 4.dp),
                        color = MaterialTheme.colorScheme.onSurfaceVariant,
                    )
                    Column(modifier = Modifier.padding(top = 12.dp)) {
                        LabeledValue(label = "Status", value = item.status)
                        LabeledValue(label = "Tanggal", value = item.submittedAt ?: "-")
                    }
                }
            }
        }
    }
}

@Composable
fun IzinDetailRoute(
    onBack: () -> Unit,
    onUnauthorized: () -> Unit,
    viewModel: IzinDetailViewModel = hiltViewModel(),
) {
    val state by viewModel.uiState.collectAsStateWithLifecycle()
    val expired by viewModel.sessionExpired.collectAsStateWithLifecycle()

    when (state) {
        UiState.Loading -> LoadingState()
        is UiState.Error -> ErrorState(
            message = (state as UiState.Error).message,
            onRetry = viewModel::loadData,
        )

        UiState.Empty -> EmptyState(
            title = "Detail izin tidak ditemukan",
            subtitle = "Pastikan endpoint detail izin sudah tersedia di Laravel.",
        )

        is UiState.Success -> IzinDetailScreen(
            data = (state as UiState.Success<IzinDetailDto>).data,
            onBack = onBack,
        )
    }

    LaunchedEffect(expired) {
        if (expired) {
            onUnauthorized()
        }
    }
}

@Composable
private fun IzinDetailScreen(
    data: IzinDetailDto,
    onBack: () -> Unit,
) {
    Scaffold(
        topBar = {
            TopAppBar(
                title = { Text("Detail Izin") },
                navigationIcon = {
                    IconButton(onClick = onBack) {
                        Icon(
                            imageVector = Icons.AutoMirrored.Filled.ArrowBack,
                            contentDescription = "Kembali",
                        )
                    }
                },
            )
        },
    ) { innerPadding ->
        val item = data.item
        SectionList(contentPadding = innerPadding) {
            item {
                Card {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Text(
                            text = item.title,
                            style = MaterialTheme.typography.titleLarge,
                            fontWeight = FontWeight.Bold,
                        )
                        Text(
                            text = item.type,
                            modifier = Modifier.padding(top = 6.dp),
                            color = MaterialTheme.colorScheme.onSurfaceVariant,
                        )
                        Column(modifier = Modifier.padding(top = 16.dp)) {
                            LabeledValue(label = "Status", value = item.status)
                            LabeledValue(label = "Tanggal", value = item.submittedAt ?: "-")
                            LabeledValue(label = "Alasan", value = item.reason ?: "-")
                        }
                    }
                }
            }
        }
    }
}
