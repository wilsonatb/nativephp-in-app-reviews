## nativephp/in-app-reviews

NativePHP plugin for requesting app reviews on Android (Google Play) and iOS (App Store).

### Installation

```bash
composer require nativephp/in-app-reviews

php artisan native:plugin:register nativephp/in-app-reviews
```

### PHP Usage (Livewire/Blade)

Use the `InAppReviews` facade to request app reviews:

@verbatim
    <code-snippet name="Requesting App Review" lang="php">
        use Nativephp\InAppReviews\Facades\InAppReviews;

        // Request app review flow
        $result = InAppReviews::requestReview();

        // Check status
        if ($result && $result->status === 'review_process_started') {
        // Review flow launched successfully
        }
    </code-snippet>
@endverbatim

### Available Methods

- `InAppReviews::requestReview(): ?object` - Requests the app review flow
- Returns: Object with `status` property (`'review_process_started'`)
- Platform behavior:
- **Android**: Google Play In-App Review flow
- **iOS**: App Store Review via StoreKit

### Events

- `InAppReviewsCompleted`: Dispatched when review process completes
- Listen with `#[OnNative(InAppReviewsCompleted::class)]`

@verbatim
    <code-snippet name="Listening for InAppReviews Events" lang="php">
        use Native\Mobile\Attributes\OnNative;
        use Nativephp\InAppReviews\Events\InAppReviewsCompleted;

        #[OnNative(InAppReviewsCompleted::class)]
        public function handleReviewCompleted($data)
        {
        // Handle the completed review
        $result = $data['result'];
        $id = $data['id'];
        }
    </code-snippet>
@endverbatim

### JavaScript Usage (Vue/React/Inertia)

@verbatim
    <code-snippet name="Using InAppReviews in JavaScript" lang="javascript">
        import { requestReview } from './vendor/nativephp/in-app-reviews/resources/js/InAppReviews.js';

        // Request review from JavaScript
        async function requestAppReview() {
        try {
        const result = await requestReview();
        console.log('Review process started:', result.status);
        return result;
        } catch (error) {
        console.error('Failed to request review:', error);
        throw error;
        }
        }
    </code-snippet>
@endverbatim

### Platform Notes

**Android:**
- Requires app to be published in Google Play (internal/alpha/beta track)
- Uses Google Play In-App Review API
- User can rate without leaving the app

**iOS:**
- Requires app to be published in TestFlight for testing
- Uses StoreKit's SKStoreReviewController
- Apple may limit review prompt frequency

### Best Practices

1. Request reviews at appropriate moments (after positive user interactions)
2. Don't request reviews too frequently
3. Test on physical devices for both platforms
4. For Android, use Google Play internal testing track
5. For iOS, use TestFlight builds
