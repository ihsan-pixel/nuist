package com.nuist.android

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.nuist.android.data.repository.AuthRepository
import dagger.hilt.android.lifecycle.HiltViewModel
import javax.inject.Inject
import kotlinx.coroutines.flow.SharingStarted
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.map
import kotlinx.coroutines.flow.stateIn

enum class SessionState {
    CHECKING,
    AUTHENTICATED,
    UNAUTHENTICATED,
}

@HiltViewModel
class MainViewModel @Inject constructor(
    authRepository: AuthRepository,
) : ViewModel() {

    val sessionState: StateFlow<SessionState> = authRepository.tokenFlow
        .map { token ->
            if (token.isNullOrBlank()) {
                SessionState.UNAUTHENTICATED
            } else {
                SessionState.AUTHENTICATED
            }
        }
        .stateIn(
            scope = viewModelScope,
            started = SharingStarted.WhileSubscribed(5_000),
            initialValue = SessionState.CHECKING,
        )
}
