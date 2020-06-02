<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use Webloyer\App\Service\Project\{
    GetProjectRequest,
    GetProjectService,
};
use Webloyer\App\Service\User\{
    GetUserRequest,
    GetUserService,
};
use Webloyer\Domain\Model\Deployment\DeploymentCompleted;
use Webloyer\Infra\Framework\Laravel\App\Notifications\DeploymentCompleted as DeploymentCompletedNotification;

class LaravelDeploymentCompletedListener implements ShouldQueue
{
    public function __construct(
        GetProjectService $getProjectService,
        GetUserService $getUserService
    ) {
        $this->getProjectService = $getProjectService;
        $this->getUserService = $getUserService;
    }

    /**
     * Handle the event.
     *
     * @param DeploymentCompleted $event
     * @return void
     */
    public function handle(DeploymentCompleted $event): void
    {
        $getProjectRequest = (new GetProjectRequest())->setId($event->projectId());
        $project = $this->getProjectService->execute($getProjectRequest);

        // view is 404, get is null object
        $getUserRequest = (new GetUserRequest())->setId($event->userId());
        $user = $this->getUserService->execute($getUserRequest);

        if (!is_null($project->emailNotificationRecipient)) {
            Notification::route('mail', $project->emailNotificationRecipient)
                ->notify(new DeploymentCompletedNotification($event, $project, $user));
        }
    }
}
