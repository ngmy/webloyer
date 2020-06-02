<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Providers;

use Illuminate\Support\ServiceProvider;

class WebloyerTranslationServiceProvider extends ServiceProvider
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
        $this->loadTranslationsFrom(__DIR__ . '/../../Resources/lang', 'webloyer');
    }
}
