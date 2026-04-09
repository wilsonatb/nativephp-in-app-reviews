package com.nativephp.plugins.in_app_reviews

import androidx.fragment.app.FragmentActivity
import android.os.Handler
import android.os.Looper
import com.google.android.play.core.review.ReviewManagerFactory
import com.google.android.play.core.review.ReviewException
import com.nativephp.mobile.bridge.BridgeFunction
import com.nativephp.mobile.bridge.BridgeResponse
import com.nativephp.mobile.bridge.BridgeError
import com.nativephp.mobile.utils.NativeActionCoordinator
import org.json.JSONObject

object InAppReviewsFunctions {

    class RequestReview(private val activity: FragmentActivity) : BridgeFunction {
        override fun execute(parameters: Map<String, Any>): Map<String, Any> {

            val manager = ReviewManagerFactory.create(activity)

            val request = manager.requestReviewFlow()

            request.addOnCompleteListener { task ->
                if (task.isSuccessful) {
                    val reviewInfo = task.result
                    val flow = manager.launchReviewFlow(activity, reviewInfo)

                    flow.addOnCompleteListener { flowTask ->
                        Handler(Looper.getMainLooper()).post {
                            val payload = JSONObject().apply {
                                put("result", if (flowTask.isSuccessful) "completed" else "error")
                                put("id", "review_flow")
                                if (!flowTask.isSuccessful) {
                                    put("error", flowTask.exception?.message ?: "Unknown error")
                                }
                            }
                            NativeActionCoordinator.dispatchEvent(
                                activity,
                                "Nativephp\\InAppReviews\\Events\\InAppReviewsCompleted",
                                payload.toString()
                            )
                        }
                    }
                } else {
                    val exception = task.exception
                    val errorCode = if (exception is ReviewException) {
                        exception.errorCode.toString()
                    } else {
                        "UNKNOWN_ERROR"
                    }
                    val errorMessage = exception?.message ?: "Failed to request review flow"

                    Handler(Looper.getMainLooper()).post {
                        val payload = JSONObject().apply {
                            put("result", "error")
                            put("id", "review_flow")
                            put("error", errorMessage)
                            put("errorCode", errorCode)
                        }
                        NativeActionCoordinator.dispatchEvent(
                            activity,
                            "Nativephp\\InAppReviews\\Events\\InAppReviewsCompleted",
                            payload.toString()
                        )
                    }
                }
            }

            return BridgeResponse.success(mapOf(
                "status" to "review_process_started"
            ))
        }
    }
}
