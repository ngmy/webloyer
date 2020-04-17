<?php

declare(strict_types=1);

namespace Webloyer;

use Illuminate\Support\ServiceProvider;
use Webloyer\Domain\Model\Recipe\RecipeRepository;
use Webloyer\Domain\Model\Server\ServerRepository;
use Webloyer\Infra\Db\Repositories\Recipe\DbRecipeRepository;
use Webloyer\Infra\Db\Repositories\Server\DbServerRepository;

class WebloyerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(RecipeRepository::class, DbRecipeRepository::class);
        $this->app->bind(ServerRepository::class, DbServerRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
