<?php

namespace Nativephp\InAppReviews;

use Illuminate\Support\ServiceProvider;
use Nativephp\InAppReviews\Commands\CopyAssetsCommand;

class InAppReviewsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(InAppReviews::class, function () {
            return new InAppReviews();
        });
    }

    public function boot(): void
    {
        // Register plugin hook commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                CopyAssetsCommand::class,
            ]);
        }
    }
}