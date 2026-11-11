package com.example.penacendekia.ui

import android.app.Activity
import android.content.Intent
import android.view.View
import android.view.animation.DecelerateInterpolator
import android.view.animation.Interpolator
import androidx.core.view.animation.PathInterpolatorCompat
import com.example.penacendekia.R

object PageTransition {

    val fastOutSlowInInterpolator: Interpolator by lazy {
        PathInterpolatorCompat.create(0.4f, 0.0f, 0.2f, 1.0f)
    }

    /**
     * Start a new activity with a smooth Material 3 slide-in transition from right.
     */
    fun slideForward(activity: Activity, intent: Intent) {
        activity.startActivity(intent)
        @Suppress("DEPRECATION")
        activity.overridePendingTransition(R.anim.slide_in_right, R.anim.slide_out_left)
    }

    /**
     * Finish current activity with a smooth Material 3 slide-out transition to right.
     */
    fun slideBackward(activity: Activity) {
        activity.finish()
        @Suppress("DEPRECATION")
        activity.overridePendingTransition(R.anim.slide_in_left, R.anim.slide_out_right)
    }

    /**
     * Start an activity with a smooth cross-fade transition.
     */
    fun fadeForward(activity: Activity, intent: Intent) {
        activity.startActivity(intent)
        @Suppress("DEPRECATION")
        activity.overridePendingTransition(R.anim.fade_in_smooth, R.anim.fade_out_smooth)
    }

    /**
     * Seamless bottom navigation tab switching without jarring flashes or activity destruction.
     */
    fun switchTab(activity: Activity, targetClass: Class<out Activity>) {
        if (activity.javaClass == targetClass) return
        val intent = Intent(activity, targetClass).apply {
            flags = Intent.FLAG_ACTIVITY_REORDER_TO_FRONT or Intent.FLAG_ACTIVITY_SINGLE_TOP
        }
        activity.startActivity(intent)
        @Suppress("DEPRECATION")
        activity.overridePendingTransition(0, 0)
    }

    /**
     * Smooth entrance animation for views in layouts.
     */
    fun animateViewIn(view: View, delayMs: Long = 0, durationMs: Long = 320, translationYDist: Float = 25f) {
        view.alpha = 0f
        view.translationY = translationYDist
        view.animate()
            .alpha(1f)
            .translationY(0f)
            .setStartDelay(delayMs)
            .setDuration(durationMs)
            .setInterpolator(fastOutSlowInInterpolator)
            .start()
    }
}
