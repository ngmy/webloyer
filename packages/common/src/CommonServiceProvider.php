<?php

namespace Ngmy\Webloyer\Common;

use Illuminate\Support\ServiceProvider;
use Ngmy\Webloyer\Common\Config\ConfigReaderInterface;
use Ngmy\Webloyer\Common\Config\ConfigWriterInterface;
use Ngmy\Webloyer\Common\Filesystem\FilesystemInterface;
use Ngmy\Webloyer\Common\Notification\NotifierInterface;
use Ngmy\Webloyer\Common\Port\Adapter\Notification\MailNotifier;
use Ngmy\Webloyer\Common\Port\Adapter\Persistence\DotenvConfigReader;
use Ngmy\Webloyer\Common\Port\Adapter\Persistence\DotenvConfigWriter;
use Ngmy\Webloyer\Common\Port\Adapter\Persistence\LaravelFilesystem;

class CommonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FilesystemInterface::class, LaravelFilesystem::class);
        $this->app->bind(NotifierInterface::class, MailNotifier::class);

        $this->app->bind(ConfigReaderInterface::class, function ($app) {
            $path = base_path('.env');

            return new DotenvConfigReader(
                $app->make(FilesystemInterface::class, [$app['files']]),
                $path
            );
        });

        $this->app->bind(ConfigWriterInterface::class, function ($app) {
            $path = base_path('.env');

            return new DotenvConfigWriter(
                $app->make(FilesystemInterface::class, [$app['files']]),
                $path
            );
        });

    }
}
