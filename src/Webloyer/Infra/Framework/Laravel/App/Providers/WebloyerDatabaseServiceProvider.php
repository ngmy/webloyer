<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Providers;

use Illuminate\Support\Facades\{
    Artisan,
    Request,
};
use Illuminate\Support\ServiceProvider;

class WebloyerDatabaseServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom(__DIR__ . '/../../Database/migrations');
        $this->loadFactoriesFrom(__DIR__ . '/../../Database/factories');
        $this->loadSeedsFrom(__DIR__ . '/../../Database/Seeds');
    }

    protected function loadSeedsFrom(string $path): void
    {
        assert(glob($path . '/*.php') !== false);
        foreach (glob($path . '/*.php') as $filename) {
            include $filename;
            $classes = get_declared_classes();
            $class = end($classes);

            $command = Request::server('argv', null);
            if (is_array($command)) {
                $command = implode(' ', $command);
                if ($command == 'artisan db:seed') {
                    Artisan::call('db:seed', ['--class' => $class]);
                }
            }
        }
    }
}
