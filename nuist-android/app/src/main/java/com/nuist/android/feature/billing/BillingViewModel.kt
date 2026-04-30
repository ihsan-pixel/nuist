package com.nuist.android.feature.billing

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.nuist.android.core.network.ApiResult
import com.nuist.android.core.ui.state.UiState
import com.nuist.android.data.remote.dto.BillingListDto
import com.nuist.android.data.repository.AuthRepository
import com.nuist.android.data.repository.BillingRepository
import dagger.hilt.android.lifecycle.HiltViewModel
import javax.inject.Inject
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch

@HiltViewModel
class BillingViewModel @Inject constructor(
    private val billingRepository: BillingRepository,
    private val authRepository: AuthRepository,
) : ViewModel() {

    private val _uiState = MutableStateFlow<UiState<BillingListDto>>(UiState.Loading)
    val uiState: StateFlow<UiState<BillingListDto>> = _uiState.asStateFlow()

    private val _sessionExpired = MutableStateFlow(false)
    val sessionExpired: StateFlow<Boolean> = _sessionExpired.asStateFlow()

    init {
        loadData()
    }

    fun loadData() {
        viewModelScope.launch {
            _uiState.value = UiState.Loading
            when (val result = billingRepository.getBills()) {
                is ApiResult.Success -> {
                    _uiState.value = if (result.data.items.isEmpty()) {
                        UiState.Empty
                    } else {
                        UiState.Success(result.data)
                    }
                }

                is ApiResult.Error -> _uiState.value = UiState.Error(result.message)
                ApiResult.Unauthorized -> {
                    authRepository.clearLocalSession()
                    _sessionExpired.value = true
                }
            }
        }
    }
}
