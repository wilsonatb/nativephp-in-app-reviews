<?php

namespace Nativephp\InAppReviews\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static object|null requestReview()
 *
 * @see \Nativephp\InAppReviews\InAppReviews
 */
class InAppReviews extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Nativephp\InAppReviews\InAppReviews::class;
    }
}