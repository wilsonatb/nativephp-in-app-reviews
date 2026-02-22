package com.nativephp.plugins.in_app_reviews

import androidx.fragment.app.FragmentActivity
import com.google.android.play.core.review.ReviewManagerFactory
import com.google.android.play.core.review.ReviewException
import com.nativephp.mobile.bridge.BridgeFunction
import com.nativephp.mobile.bridge.BridgeResponse

object InAppReviewsFunctions {

    class RequestReview(private val activity: FragmentActivity) : BridgeFunction {
        override fun execute(parameters: Map<String, Any>): Map<String, Any> {

            val manager = ReviewManagerFactory.create(activity)

            val request = manager.requestReviewFlow()

            request.addOnCompleteListener { task ->
                if (task.isSuccessful) {
                    val reviewInfo = task.result
                    val flow = manager.launchReviewFlow(activity, reviewInfo)

                    flow.addOnCompleteListener { _ ->

                    }
                } else {
                    val exception = task.exception
                    if (exception is ReviewException) {
                        val reviewErrorCode = exception.errorCode
                        println("Google Play Review Error Code: $reviewErrorCode")
                    }
                }
            }

            return BridgeResponse.success(mapOf(
                "status" to "review_process_started"
            ))
        }
    }
}
