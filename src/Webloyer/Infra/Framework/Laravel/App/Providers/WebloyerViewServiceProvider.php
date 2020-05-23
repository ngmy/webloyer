<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Providers;

use Illuminate\Support\ServiceProvider;

class WebloyerViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'webloyer');
    }
}
