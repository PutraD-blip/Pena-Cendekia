package com.example.penacendekia.ui

import android.content.Context
import android.util.AttributeSet
import android.view.MotionEvent
import android.widget.HorizontalScrollView
import kotlin.math.abs

/**
 * A HorizontalScrollView that seamlessly cooperates with a parent NestedScrollView.
 * When the user drags vertically (dy > dx), it does not intercept touch events,
 * allowing the parent NestedScrollView to scroll vertically with zero lag.
 */
class NestedHorizontalScrollView @JvmOverloads constructor(
    context: Context,
    attrs: AttributeSet? = null,
    defStyleAttr: Int = 0
) : HorizontalScrollView(context, attrs, defStyleAttr) {

    private var startX = 0f
    private var startY = 0f

    override fun onInterceptTouchEvent(ev: MotionEvent): Boolean {
        when (ev.action) {
            MotionEvent.ACTION_DOWN -> {
                startX = ev.x
                startY = ev.y
            }
            MotionEvent.ACTION_MOVE -> {
                val dx = abs(ev.x - startX)
                val dy = abs(ev.y - startY)
                if (dy > dx) {
                    // Vertical drag! Let the parent NestedScrollView handle scrolling.
                    return false
                }
            }
        }
        return super.onInterceptTouchEvent(ev)
    }
}
