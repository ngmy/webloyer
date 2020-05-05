<?php

declare(strict_types=1);

namespace Webloyer\Infra\Messaging;

use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Webloyer\Domain\Model\Deployment;
use Webloyer\Infra\Notification\Laravel\{
    DeploymentWasFinishedNotifiable,
    DeploymentWasFinishedNotification,
    DeploymentWasFinishedNotificationDto,
};

class SendNotificationWhenDeploymentWasFinishedEventListener implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param Deployment\DeploymentWasFinishedEvent $event
     * @return void
     */
    public function handle(Deployment\DeploymentWasFinishedEvent $event): void
    {
        $notifiable = new DeploymentWasFinishedNotifiable();
        $event->project()->provide($notifiable);

        $dto = new DeploymentWasFinishedNotificationDto();
        $event->deployment()->provide($dto);
        $event->project()->provide($dto);

        $notifiable->notify(new DeploymentWasFinishedNotification($dto));
    }
}
