<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Webloyer\Infra\Framework\Laravel\App\Console\Commands\{
    DiscardOldDeployments,
    Install,
};

class WebloyerCommandServiceProvider extends ServiceProvider
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
        if ($this->app->runningInConsole()) {
            $this->commands([
                DiscardOldDeployments::class,
                Install::class,
            ]);
        }

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('webloyer:discard-old-deployments')
                ->everyMinute()
                ->withoutOverlapping();
        });
    }
}
