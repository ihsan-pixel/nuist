package com.nuist.android.feature.dashboard

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.nuist.android.core.network.ApiResult
import com.nuist.android.core.ui.state.UiState
import com.nuist.android.data.remote.dto.DashboardDto
import com.nuist.android.data.repository.AuthRepository
import com.nuist.android.data.repository.DashboardRepository
import dagger.hilt.android.lifecycle.HiltViewModel
import javax.inject.Inject
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch

@HiltViewModel
class DashboardViewModel @Inject constructor(
    private val dashboardRepository: DashboardRepository,
    private val authRepository: AuthRepository,
) : ViewModel() {

    private val _uiState = MutableStateFlow<UiState<DashboardDto>>(UiState.Loading)
    val uiState: StateFlow<UiState<DashboardDto>> = _uiState.asStateFlow()

    private val _sessionExpired = MutableStateFlow(false)
    val sessionExpired: StateFlow<Boolean> = _sessionExpired.asStateFlow()

    init {
        loadData()
    }

    fun loadData() {
        viewModelScope.launch {
            _uiState.value = UiState.Loading
            when (val result = dashboardRepository.getDashboard()) {
                is ApiResult.Success -> _uiState.value = UiState.Success(result.data)
                is ApiResult.Error -> _uiState.value = UiState.Error(result.message)
                ApiResult.Unauthorized -> {
                    authRepository.clearLocalSession()
                    _sessionExpired.value = true
                }
            }
        }
    }
}
