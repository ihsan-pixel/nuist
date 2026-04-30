package com.nuist.android.feature.auth

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.nuist.android.core.network.ApiResult
import com.nuist.android.data.repository.AuthRepository
import dagger.hilt.android.lifecycle.HiltViewModel
import javax.inject.Inject
import kotlinx.coroutines.flow.MutableSharedFlow
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.SharedFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asSharedFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.flow.update
import kotlinx.coroutines.launch

data class LoginUiState(
    val email: String = "",
    val password: String = "",
    val isLoading: Boolean = false,
    val errorMessage: String? = null,
)

enum class LoginEvent {
    Success,
}

@HiltViewModel
class LoginViewModel @Inject constructor(
    private val authRepository: AuthRepository,
) : ViewModel() {

    private val _uiState = MutableStateFlow(LoginUiState())
    val uiState: StateFlow<LoginUiState> = _uiState.asStateFlow()

    private val _events = MutableSharedFlow<LoginEvent>()
    val events: SharedFlow<LoginEvent> = _events.asSharedFlow()

    fun updateEmail(value: String) {
        _uiState.update { current -> current.copy(email = value, errorMessage = null) }
    }

    fun updatePassword(value: String) {
        _uiState.update { current -> current.copy(password = value, errorMessage = null) }
    }

    fun login() {
        val state = _uiState.value
        if (state.email.isBlank() || state.password.isBlank()) {
            _uiState.update { current ->
                current.copy(errorMessage = "Email dan password wajib diisi.")
            }
            return
        }

        viewModelScope.launch {
            _uiState.update { current -> current.copy(isLoading = true, errorMessage = null) }

            when (val result = authRepository.login(state.email, state.password)) {
                is ApiResult.Success -> {
                    _uiState.update { current -> current.copy(isLoading = false) }
                    _events.emit(LoginEvent.Success)
                }

                is ApiResult.Error -> {
                    _uiState.update { current ->
                        current.copy(isLoading = false, errorMessage = result.message)
                    }
                }

                ApiResult.Unauthorized -> {
                    _uiState.update { current ->
                        current.copy(isLoading = false, errorMessage = "Login ditolak.")
                    }
                }
            }
        }
    }
}
