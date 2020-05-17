<?php

namespace App\Providers;

use App\Services\Notification\MailNotifier;
use App\Services\Api\JsonRpc;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\Notification\NotifierInterface', function ($app) {
            return new MailNotifier();
        });

//        $this->app->bind('App\Services\Api\JsonRpc', function ($app) {
//            return new JsonRpc(
//                $app->make('App\Repositories\Project\ProjectInterface'),
//                $app->make('App\Services\Form\Deployment\DeploymentForm')
//            );
//        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
