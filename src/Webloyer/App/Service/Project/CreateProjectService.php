<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Project;

use Webloyer\Domain\Model\Project\Project;

class CreateProjectService extends ProjectService
{
    /**
     * @param CreateProjectRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $project = Project::of(
            $this->projectRepository->nextId()->value(),
            $request->getName(),
            $request->getRecipeIds(),
            $request->getServerId(),
            $request->getRepositoryUrl(),
            $request->getStageName(),
            $request->getDeployPath(),
            $request->getEmailNotificationRecipient(),
            $request->getDeploymentKeepDays(),
            $request->getKeepLastDeployment(),
            $request->getDeploymentKeepMaxNumber(),
            $request->getGithubWebhookSecret(),
            $request->getGithubWebhookExecutor()
        );
        $this->projectRepository->save($project);
    }
}
