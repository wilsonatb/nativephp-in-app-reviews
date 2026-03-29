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
                        // iOS 16 requiere async/await.
                        Task { @MainActor in
                            try? await AppStore.requestReview(in: scene)
                        }
                    }
                } else if #available(iOS 14.0, *) {
                    if let scene = activeScene {
                        SKStoreReviewController.requestReview(in: scene)
                    }
                } else {
                    SKStoreReviewController.requestReview()
                }
            }

            return [
                "success": true,
                "status": "review_process_started"
            ]
        }
    }
}
