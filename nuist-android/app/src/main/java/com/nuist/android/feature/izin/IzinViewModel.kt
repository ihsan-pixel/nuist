package com.nuist.android.feature.izin

import androidx.lifecycle.SavedStateHandle
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.nuist.android.core.network.ApiResult
import com.nuist.android.core.ui.state.UiState
import com.nuist.android.data.remote.dto.IzinDetailDto
import com.nuist.android.data.remote.dto.IzinListDto
import com.nuist.android.data.repository.AuthRepository
import com.nuist.android.data.repository.IzinRepository
import dagger.hilt.android.lifecycle.HiltViewModel
import javax.inject.Inject
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch

@HiltViewModel
class IzinViewModel @Inject constructor(
    private val izinRepository: IzinRepository,
    private val authRepository: AuthRepository,
) : ViewModel() {

    private val _uiState = MutableStateFlow<UiState<IzinListDto>>(UiState.Loading)
    val uiState: StateFlow<UiState<IzinListDto>> = _uiState.asStateFlow()

    private val _sessionExpired = MutableStateFlow(false)
    val sessionExpired: StateFlow<Boolean> = _sessionExpired.asStateFlow()

    init {
        loadData()
    }

    fun loadData() {
        viewModelScope.launch {
            _uiState.value = UiState.Loading
            when (val result = izinRepository.getIzinList()) {
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

@HiltViewModel
class IzinDetailViewModel @Inject constructor(
    private val izinRepository: IzinRepository,
    private val authRepository: AuthRepository,
    savedStateHandle: SavedStateHandle,
) : ViewModel() {

    private val izinId: Long? = savedStateHandle.get<String>("izinId")?.toLongOrNull()

    private val _uiState = MutableStateFlow<UiState<IzinDetailDto>>(UiState.Loading)
    val uiState: StateFlow<UiState<IzinDetailDto>> = _uiState.asStateFlow()

    private val _sessionExpired = MutableStateFlow(false)
    val sessionExpired: StateFlow<Boolean> = _sessionExpired.asStateFlow()

    init {
        loadData()
    }

    fun loadData() {
        val currentId = izinId
        if (currentId == null) {
            _uiState.value = UiState.Error("ID izin tidak valid.")
            return
        }

        viewModelScope.launch {
            _uiState.value = UiState.Loading
            when (val result = izinRepository.getIzinDetail(currentId)) {
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
