<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Providers;

use Illuminate\Support\ServiceProvider;
use Kodeine\Acl\Middleware\HasPermission;
use Webloyer\Infra\Framework\Laravel\App\Http\Middleware\{
    ResponseJson,
    ResponseJsonIfRequestedByQueryParameter,
    VerifyGitHubWebhookSecret,
};

class WebloyerMiddlewareServiceProvider extends ServiceProvider
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
        $this->app['router']->aliasMiddleware('acl', HasPermission::class);
        $this->app['router']->aliasMiddleware('github_webhook_secret', VerifyGitHubWebhookSecret::class);
        $this->app['router']->aliasMiddleware('response_json', ResponseJson::class);
        $this->app['router']->aliasMiddleware('response_json.if_requested_by_query', ResponseJsonIfRequestedByQueryParameter::class);
    }
}
