package com.nuist.android.feature.profile

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
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
import com.nuist.android.core.ui.components.LabeledValue
import com.nuist.android.core.ui.components.LoadingState
import com.nuist.android.core.ui.components.SectionList
import com.nuist.android.core.ui.state.UiState
import com.nuist.android.data.remote.dto.UserDto

@Composable
fun ProfileRoute(
    onLoggedOut: () -> Unit,
    viewModel: ProfileViewModel = hiltViewModel(),
) {
    val state by viewModel.uiState.collectAsStateWithLifecycle()

    LaunchedEffect(viewModel) {
        viewModel.events.collect {
            onLoggedOut()
        }
    }

    when (state) {
        UiState.Loading -> LoadingState()
        is UiState.Error -> ErrorState(
            message = (state as UiState.Error).message,
            onRetry = viewModel::loadData,
        )

        UiState.Empty -> ErrorState(
            message = "Profil belum tersedia.",
            onRetry = viewModel::loadData,
        )

        is UiState.Success -> ProfileScreen(
            user = (state as UiState.Success<UserDto>).data,
            isLoggingOut = viewModel.isLoggingOut.collectAsStateWithLifecycle().value,
            onLogout = viewModel::logout,
        )
    }
}

@Composable
private fun ProfileScreen(
    user: UserDto,
    isLoggingOut: Boolean,
    onLogout: () -> Unit,
) {
    SectionList {
        item {
            Card {
                Column(modifier = Modifier.padding(16.dp)) {
                    Text(
                        text = user.name,
                        style = MaterialTheme.typography.titleLarge,
                        fontWeight = FontWeight.Bold,
                    )
                    Text(
                        text = user.email,
                        modifier = Modifier.padding(top = 4.dp),
                        color = MaterialTheme.colorScheme.onSurfaceVariant,
                    )
                    Column(
                        modifier = Modifier.padding(top = 16.dp),
                        verticalArrangement = Arrangement.spacedBy(10.dp),
                    ) {
                        LabeledValue(label = "Role", value = user.role)
                        LabeledValue(label = "Nuist ID", value = user.nuist_id ?: "-")
                    }
                }
            }
        }

        item {
            Button(
                onClick = onLogout,
                modifier = Modifier.fillMaxWidth(),
                enabled = !isLoggingOut,
            ) {
                Text(if (isLoggingOut) "Keluar..." else "Logout")
            }
        }
    }
}
