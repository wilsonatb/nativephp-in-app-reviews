import Foundation
import StoreKit
import UIKit

// BridgeResponse is globally available from the NativePHP bridge

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
                            try? await AppStore.requestReview(in: scene) // Errors ignored; iOS provides no completion callbacks
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

            // iOS no provee callbacks para saber cuándo termina la revisión
            // El evento InAppReviewsCompleted solo se dispara en Android
            return BridgeResponse.success(data: [
                "status": "review_process_started",
                "platform": "ios",
                "note": "iOS does not provide completion callbacks for review flow"
            ])
        }
    }
}
