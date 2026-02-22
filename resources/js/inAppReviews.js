/**
 * InAppReviews Plugin for NativePHP Mobile
 *
 * @example
 * import { requestReview } from '@nativephp/in-app-reviews';
 *
 * // Request Google Play Review flow
 * const result = await requestReview();
 */

const baseUrl = '/_native/api/call';

/**
 * Internal bridge call function
 * @private
 */
async function bridgeCall(method, params = {}) {
    const response = await fetch(baseUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ method, params })
    });

    const result = await response.json();

    if (result.status === 'error') {
        throw new Error(result.message || 'Native call failed');
    }

    const nativeResponse = result.data;
    if (nativeResponse && nativeResponse.data !== undefined) {
        return nativeResponse.data;
    }

    return nativeResponse;
}

/**
 * Request In App Review flow
 * @returns {Promise<Object>} Returns status object
 */
export async function requestReview() {
    return bridgeCall('InAppReviews.RequestReview', {});
}

/**
 * InAppReviews namespace object
 */
export const inAppReviews = {
    requestReview
};

export default inAppReviews;
