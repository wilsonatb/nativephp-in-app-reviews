<?php

namespace Nativephp\InAppReviews;

class InAppReviews
{
    /**
     * Request a review from the user. This will trigger the native review prompt on supported platforms.
     */
    public function requestReview(): ?object
    {
        if (function_exists('nativephp_call')) {
            $result = nativephp_call('InAppReviews.RequestReview', '{}');

            if ($result) {
                $decoded = json_decode($result);
                return $decoded->data ?? null;
            }
        }

        return null;
    }
}
