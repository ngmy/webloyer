<?php

namespace Ngmy\Webloyer\Webloyer\Application\Project;

use DB;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\KeepLastDeployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectAttribute;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;

class ProjectService
{
    private $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    public function getAllProjects()
    {
        return $this->projectRepository->allProjects();
    }

    public function getProjectOfId($id)
    {
        return $this->projectRepository->projectOfId(new ProjectId($id));
    }

    public function getProjectsOfPage($page = 1, $perPage = 10)
    {
        return $this->projectRepository->projectsOfPage($page, $perPage);
    }

    public function saveProject($id, $name, array $recipeIds, $serverId, $repositoryUrl, $stage, $deployPath, $emailNotificationRecipient, $daysToKeepDeployments, $maxNumberOfDeploymentsToKeep, $keepLastDeployment, $githubWebhookSecret, $githubWebhookExecuteUserId, $concurrencyVersion)
    {
        $project = DB::transaction(function () use ($id, $name, $recipeIds, $serverId, $repositoryUrl, $stage, $deployPath, $emailNotificationRecipient, $daysToKeepDeployments, $maxNumberOfDeploymentsToKeep, $keepLastDeployment, $githubWebhookSecret, $githubWebhookExecuteUserId, $concurrencyVersion) {
            if (!is_null($id)) {
                $existsProject = $this->getProjectOfId($id);

                if (!is_null($existsProject)) {
                    $existsProject->failWhenConcurrencyViolation($concurrencyVersion);
                }
            }

            $project = new Project(
                new ProjectId($id),
                $name,
                array_map(function ($recipeId) {
                    return new RecipeId($recipeId);
                }, $recipeIds),
                new ServerId($serverId),
                $repositoryUrl,
                $stage,
                new ProjectAttribute($deployPath),
                $emailNotificationRecipient,
                $daysToKeepDeployments,
                $maxNumberOfDeploymentsToKeep,
                new KeepLastDeployment($keepLastDeployment),
                $githubWebhookSecret,
                new UserId($githubWebhookExecuteUserId),
                null,
                null
            );

            $this->projectRepository->save($project);
        });

        return $project;
    }

    public function removeProject($id)
    {
        return $this->projectRepository->remove($this->getProjectOfId($id));
    }
}
