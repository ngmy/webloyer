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

    /**
     * Create a new application service instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectRepositoryInterface $projectRepository
     * @return void
     */
    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * Get all projects.
     *
     * @return array
     */
    public function getAllProjects()
    {
        return $this->projectRepository->allProjects();
    }

    /**
     * Get projects by page.
     *
     * @param int $page
     * @param int $perPage
     * @return void
     */
    public function getProjectsByPage($page = 1, $perPage = 10)
    {
        return $this->projectRepository->projectsOfPage($page, $perPage);
    }

    /**
     * Get a project by id.
     *
     * @param int $projectId
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project
     */
    public function getProjectById($projectId)
    {
        return $this->projectRepository->projectOfId(new ProjectId($projectId));
    }

    /**
     * Create or Update a project.
     *
     * @param int|null $projectId
     * @param string   $name
     * @param int[]    $recipeIds
     * @param int      $serverId
     * @param string   $repositoryUrl
     * @param string   $stage
     * @param string   $deployPath
     * @param string   $emailNotificationRecipient
     * @param int      $daysToKeepDeployments
     * @param int      $maxNumberOfDeploymentsToKeep
     * @param int      $keepLastDeployment
     * @param string   $githubWebhookSecret
     * @param int      $githubWebhookExecuteUserId
     * @param string   $concurrencyVersion
     * @return void
     */
    public function saveProject($projectId, $name, array $recipeIds, $serverId, $repositoryUrl, $stage, $deployPath, $emailNotificationRecipient, $daysToKeepDeployments, $maxNumberOfDeploymentsToKeep, $keepLastDeployment, $githubWebhookSecret, $githubWebhookExecuteUserId, $concurrencyVersion)
    {
        DB::transaction(function () use ($projectId, $name, $recipeIds, $serverId, $repositoryUrl, $stage, $deployPath, $emailNotificationRecipient, $daysToKeepDeployments, $maxNumberOfDeploymentsToKeep, $keepLastDeployment, $githubWebhookSecret, $githubWebhookExecuteUserId, $concurrencyVersion) {
            if (!is_null($projectId)) {
                $existsProject = $this->getProjectById($projectId);
                if (!is_null($existsProject)) {
                    $existsProject->failWhenConcurrencyViolation($concurrencyVersion);
                }
            }
            $project = new Project(
                new ProjectId($projectId),
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
    }

    /**
     * Remove a project.
     *
     * @param int $projectId
     * @return void
     */
    public function removeProject($projectId)
    {
        $this->projectRepository->remove($this->getProjectById($projectId));
    }
}
