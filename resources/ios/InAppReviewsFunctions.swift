import Foundation
import StoreKit
import UIKit

enum InAppReviewsFunctions {

    class RequestReview: BridgeFunction {
        func execute(parameters: [String: Any]) throws -> [String: Any] {

            DispatchQueue.main.async {
                let activeScene = UIApplication.shared.connectedScenes
                    .first(where: { $0.activationState == .foregroundActive }) as? UIWindowScene

                if #available(iOS 16.0, *) {
                    if let scene = activeScene {
                        EnvironmentValues().requestReview
                        AppStore.requestReview(in: scene)
                    }
                } else if #available(iOS 14.0, *) {
                    // Fallback for iOS 14 and 15
                    if let scene = activeScene {
                        SKStoreReviewController.requestReview(in: scene)
                    }
                } else {
                    // Fallback for iOS 13 and earlier
                    SKStoreReviewController.requestReview()
                }
            }

            return BridgeResponse.success(data: [
                "status": "review_process_started"
            ])
        }
    }
}
