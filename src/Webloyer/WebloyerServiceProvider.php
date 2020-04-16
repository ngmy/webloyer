<?php

declare(strict_types=1);

namespace Webloyer;

use Illuminate\Support\ServiceProvider;
use Webloyer\Domain\Model\Recipe\RecipeRepository;
use Webloyer\Infra\Db\Repositories\Recipe\DbRecipeRepository;

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
