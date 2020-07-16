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
use Illuminate\Pagination\LengthAwarePaginator;

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
    public function getAllProjects(): array
    {
        return $this->projectRepository->allProjects();
    }

    /**
     * Get projects by page.
     *
     * @param int $page
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getProjectsByPage(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        return $this->projectRepository->projectsOfPage($page, $perPage);
    }

    /**
     * Get a project by id.
     *
     * @param int $projectId
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project|null
     */
    public function getProjectById(int $projectId): ?Project
    {
        return $this->projectRepository->projectOfId(new ProjectId($projectId));
    }

    /**
     * Create or Update a project.
     *
     * @param int|null    $projectId
     * @param string      $name
     * @param int[]       $recipeIds
     * @param int         $serverId
     * @param string      $repositoryUrl
     * @param string      $stage
     * @param string      $deployPath
     * @param string      $emailNotificationRecipient
     * @param int         $daysToKeepDeployments
     * @param int         $maxNumberOfDeploymentsToKeep
     * @param int         $keepLastDeployment
     * @param string      $githubWebhookSecret
     * @param int         $githubWebhookExecuteUserId
     * @param string|null $concurrencyVersion
     * @return void
     */
    public function saveProject(?int $projectId, string $name, array $recipeIds, int $serverId, string $repositoryUrl, string $stage, string $deployPath, string $emailNotificationRecipient, int $daysToKeepDeployments, int $maxNumberOfDeploymentsToKeep, int $keepLastDeployment, string $githubWebhookSecret, int $githubWebhookExecuteUserId, ?string $concurrencyVersion): void
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
    public function removeProject(int $projectId): void
    {
        $this->projectRepository->remove($this->getProjectById($projectId));
    }
}
