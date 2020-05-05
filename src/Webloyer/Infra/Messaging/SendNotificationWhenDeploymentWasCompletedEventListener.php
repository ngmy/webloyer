<?php

declare(strict_types=1);

namespace Webloyer\Infra\Messaging;

use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Webloyer\Domain\Model\Deployment;
use Webloyer\Domain\Model\Project;
use Webloyer\Infra\Notification\Laravel\{
    DeploymentWasCompletedNotifiable,
    DeploymentWasCompletedNotification,
    DeploymentWasCompletedNotificationDto,
};

class SendNotificationWhenDeploymentWasCompletedEventListener implements ShouldQueue
{
    /** @var Deployment\DeploymentRepository */
    private $deploymentRepository;
    /** @var Project\ProjectRepository */
    private $projectRepository;

    /**
     * Create the event listener.
     *
     * @param Project\ProjectRepository       $projectRepository
     * @param Deployment\DeploymentRepository $deploymentRepository
     * @return void
     */
    public function __construct(
        Project\ProjectRepositoryt $projectRepository,
        Deployment\DeploymentRepository $deploymentRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->deploymentRepository = $deploymentRepository;
    }

    /**
     * Handle the event.
     *
     * @param Deployment\DeploymentWasCompletedEvent $event
     * @return void
     */
    public function handle(Deployment\DeploymentWasCompletedEvent $event): void
    {
        $deployment = $tthis->deploymentRepository->findById(
            new Project\ProjectId($event->projectId()),
            new Deployment\DeploymentNumber($event->number())
        );
        $project = $this->projectRepository->findById(
            new Project\ProjectId($event->projectId())
        );

        $notifiable = new DeploymentWasCompletedNotifiable();
        $project->provide($notifiable);

        $dto = new DeploymentWasCompletedNotificationDto();
        $deployment->provide($dto);
        $project->provide($dto);

        $notifiable->notify(new DeploymentWasCompletedNotification($dto));
    }
}
