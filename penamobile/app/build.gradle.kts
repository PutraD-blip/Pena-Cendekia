plugins {
    alias(libs.plugins.android.application)
}

android {
    namespace = "com.example.penacendekia"
    compileSdk {
        version = release(37)
    }

    defaultConfig {
        applicationId = "com.example.penacendekia"
        minSdk = 29
        targetSdk = 37
        versionCode = 1
        versionName = "1.0"

        testInstrumentationRunner = "androidx.test.runner.AndroidJUnitRunner"
    }

    buildTypes {
        release {
            optimization {
                enable = false
            }
        }
    }
    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_11
        targetCompatibility = JavaVersion.VERSION_11
    }
}

dependencies {
    implementation(libs.androidx.activity.ktx)
    implementation(libs.androidx.appcompat)
    implementation(libs.androidx.constraintlayout)
    implementation(libs.androidx.core.ktx)
    implementation(libs.material)
    implementation("com.google.android.flexbox:flexbox:3.0.0")

    // Retrofit & Network REST API
    implementation("com.squareup.retrofit2:retrofit:2.11.0")
    implementation("com.squareup.retrofit2:converter-gson:2.11.0")
    implementation("com.squareup.okhttp3:logging-interceptor:4.12.0")

    // Image Loading
    implementation("com.github.bumptech.glide:glide:4.16.0")

    // Google Sign-In (Google Play Services Auth)
    implementation("com.google.android.gms:play-services-auth:21.2.0")

    // ML Kit On-Device Text Recognition for Word-like text selection in PDF
    implementation("com.google.mlkit:text-recognition:16.0.1")

    testImplementation(libs.junit)
    androidTestImplementation(libs.androidx.espresso.core)
    androidTestImplementation(libs.androidx.junit)
}