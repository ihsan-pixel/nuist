package com.example.nuist_flutter_mobile

import android.app.Application
import com.google.firebase.FirebaseApp
import com.google.firebase.FirebaseOptions

class NuistApplication : Application() {
    override fun onCreate() {
        super.onCreate()

        if (FirebaseApp.getApps(this).isNotEmpty()) {
            return
        }

        val options = FirebaseOptions.Builder()
            .setApiKey("AIzaSyApu75m8BaJBOxFZ8jrfkIsSwy5fDo3c68")
            .setApplicationId("1:442645883080:android:ee92e97594562dc3e21dfd")
            .setGcmSenderId("442645883080")
            .setProjectId("nuist-mobile")
            .setStorageBucket("nuist-mobile.firebasestorage.app")
            .build()

        FirebaseApp.initializeApp(this, options)
    }
}
