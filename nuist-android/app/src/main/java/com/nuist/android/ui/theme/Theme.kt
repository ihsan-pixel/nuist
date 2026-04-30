package com.nuist.android.ui.theme

import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.darkColorScheme
import androidx.compose.material3.lightColorScheme
import androidx.compose.runtime.Composable

private val LightColors = lightColorScheme(
    primary = NuistBlue,
    secondary = NuistSand,
    tertiary = NuistBlueLight,
)

private val DarkColors = darkColorScheme(
    primary = NuistBlueLight,
    secondary = NuistSand,
    tertiary = NuistBlue,
)

@Composable
fun NuistAndroidTheme(
    content: @Composable () -> Unit,
) {
    MaterialTheme(
        colorScheme = LightColors,
        typography = Typography,
        content = content,
    )
}
