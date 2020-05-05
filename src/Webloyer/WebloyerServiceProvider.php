<?php

declare(strict_types=1);

namespace Webloyer;

use Event;
use Illuminate\Support\ServiceProvider;
use Webloyer\Domain\Model\Deployment\DeploymentRepository;
use Webloyer\Domain\Model\Deployment\DeploymentWasFinishedEvent;
use Webloyer\Domain\Model\Deployment\DeploymentWasStartedEvent;
use Webloyer\Domain\Model\Project\ProjectRepository;
use Webloyer\Domain\Model\Recipe\RecipeRepository;
use Webloyer\Domain\Model\Server\ServerRepository;
use Webloyer\Domain\Model\Setting\Mail\MailSettingRepository;
use Webloyer\Domain\Model\User\UserRepository;
use Webloyer\Infra\Messaging\RunDeployerWhenDeploymentWasStartedEventListener;
use Webloyer\Infra\Messaging\SendNotificationWhenDeploymentWasFinishedEventListener;
use Webloyer\Infra\Db\Repositories\Deployment\DbDeploymentRepository;
use Webloyer\Infra\Db\Repositories\Project\DbProjectRepository;
use Webloyer\Infra\Db\Repositories\Recipe\DbRecipeRepository;
use Webloyer\Infra\Db\Repositories\Server\DbServerRepository;
use Webloyer\Infra\Db\Repositories\Setting\Mail\DbMailSettingRepository;
use Webloyer\Infra\Db\Repositories\User\DbUserRepository;

class WebloyerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(DeploymentRepository::class, DbDeploymentRepository::class);
        $this->app->bind(MailSettingRepository::class, DbMailSettingRepository::class);
        $this->app->bind(ProjectRepository::class, DbProjectRepository::class);
        $this->app->bind(RecipeRepository::class, DbRecipeRepository::class);
        $this->app->bind(ServerRepository::class, DbServerRepository::class);
        $this->app->bind(UserRepository::class, DbUserRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        Event::listen(DeploymentWasFinishedEvent::class, SendNotificationWhenDeploymentWasFinishedEventListener::class);
        Event::listen(DeploymentWasStartedEvent::class, RunDeployerWhenDeploymentWasStartedEventListener::class);
    }
}
