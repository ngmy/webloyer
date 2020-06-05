<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\{
    DomainEvent,
    DomainEventSubscriber,
};
use Illuminate\Support\Facades\Notification;
use Webloyer\Domain\Model\Project\{
    ProjectId,
    ProjectRepository,
};
use Webloyer\Domain\Model\User\{
    UserId,
    UserRepository,
};
use Webloyer\Infra\Framework\Laravel\App\Notifications\DeploymentCompleted as DeploymentCompletedNotification;

class DeploymentCompletedSubscriber implements DomainEventSubscriber
{
    private $projectRepository;
    private $userRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        UserRepository $userRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param DomainEvent $domainEvent
     * @return void
     */
    public function handle(DomainEvent $domainEvent): void
    {
        $project = $this->projectRepository->findById(new ProjectId($domainEvent->projectId()));

        // view is 404, get is null object
        $user = $this->userRepository->findById(new UserId($domainEvent->userId()));

        if (!is_null($project->emailNotification()->recipient())) {
            // TODO this is infra
            Notification::route('mail', $project->emailNotification()->recipient())
                ->notify(new DeploymentCompletedNotification($domainEvent, $project, $user));
        }
    }

    /**
     * @param DomainEvent $domainEvent
     * @return bool
     */
    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return $domainEvent instanceof DeploymentCompleted;
    }
}
