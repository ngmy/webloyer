<?php

declare(strict_types=1);

namespace Webloyer\Infra\Messaging\Laravel;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Webloyer\Domain\Model\Deployment;
use Webloyer\Infra\Notification\Laravel\{
    DeploymentWasCompletedNotifiable,
    DeploymentWasCompletedNotification,
    DeploymentWasCompletedNotificationDto,
};

class LaravelDeploymentCompletedListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param Deployment\DeploymentCompleted $event
     * @return void
     */
    public function handle(Deployment\DeploymentCompleted $event): void
    {
        $notifiable = new DeploymentWasCompletedNotifiable();
        $event->project()->provide($notifiable);

        $dto = new DeploymentWasCompletedNotificationDto();
        $event->deployment()->provide($dto);
        $event->project()->provide($dto);

        $notifiable->notify(new DeploymentWasCompletedNotification($dto));
    }
}
