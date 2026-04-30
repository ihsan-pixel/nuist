package com.nuist.android.feature.profile

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.nuist.android.core.network.ApiResult
import com.nuist.android.core.ui.state.UiState
import com.nuist.android.data.remote.dto.UserDto
import com.nuist.android.data.repository.AuthRepository
import dagger.hilt.android.lifecycle.HiltViewModel
import javax.inject.Inject
import kotlinx.coroutines.flow.MutableSharedFlow
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.SharedFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asSharedFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch

@HiltViewModel
class ProfileViewModel @Inject constructor(
    private val authRepository: AuthRepository,
) : ViewModel() {

    private val _uiState = MutableStateFlow<UiState<UserDto>>(UiState.Loading)
    val uiState: StateFlow<UiState<UserDto>> = _uiState.asStateFlow()

    private val _isLoggingOut = MutableStateFlow(false)
    val isLoggingOut: StateFlow<Boolean> = _isLoggingOut.asStateFlow()

    private val _events = MutableSharedFlow<Unit>()
    val events: SharedFlow<Unit> = _events.asSharedFlow()

    init {
        loadData()
    }

    fun loadData() {
        viewModelScope.launch {
            _uiState.value = UiState.Loading
            when (val result = authRepository.getMe()) {
                is ApiResult.Success -> _uiState.value = UiState.Success(result.data)
                is ApiResult.Error -> _uiState.value = UiState.Error(result.message)
                ApiResult.Unauthorized -> {
                    authRepository.clearLocalSession()
                    _events.emit(Unit)
                }
            }
        }
    }

    fun logout() {
        viewModelScope.launch {
            _isLoggingOut.value = true
            authRepository.logout()
            _isLoggingOut.value = false
            _events.emit(Unit)
        }
    }
}
