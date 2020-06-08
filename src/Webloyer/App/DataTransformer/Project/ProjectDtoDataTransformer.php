<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Project;

use Webloyer\App\DataTransformer\Deployment\DeploymentDataTransformer;
use Webloyer\App\DataTransformer\Recipe\RecipesDataTransformer;
use Webloyer\App\DataTransformer\Server\ServerDataTransformer;
use Webloyer\App\DataTransformer\User\UserDataTransformer;
use Webloyer\Domain\Model\Project\{
    Project,
    ProjectId,
    ProjectInterest,
    ProjectService,
};
use Webloyer\Domain\Model\Recipe\RecipeIds;
use Webloyer\Domain\Model\Server\ServerId;
use Webloyer\Domain\Model\User\UserId;

class ProjectDtoDataTransformer implements ProjectDataTransformer
{
    private $project;
    private $projectService;
    private $deploymentDataTransformer;
    private $recipesDataTransformer;
    private $serverDataTransformer;
    private $userDataTransformer;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * @param Project $project
     * @return self
     */
    public function write(Project $project): self
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return object
     */
    public function read()
    {
        $dto = new class implements ProjectInterest {
            public function informId(string $id): void
            {
                $this->id = $id;
            }
            public function informName(string $name): void
            {
                $this->name = $name;
            }
            public function informRecipeIds(string ...$recipeIds): void
            {
                $this->recipeIds = $recipeIds;
            }
            public function informServerId(string $serverId): void
            {
                $this->serverId = $serverId;
            }
            public function informRepositoryUrl(string $repositoryUrl): void
            {
                $this->repositoryUrl = $repositoryUrl;
            }
            public function informStageName(string $stageName): void
            {
                $this->stageName = $stageName;
            }
            public function informDeployPath(?string $deployPath): void
            {
                $this->deployPath = $deployPath;
            }
            public function informEmailNotificationRecipient(?string $emailNotificationRecipient): void
            {
                $this->emailNotificationRecipient = $emailNotificationRecipient;
            }
            public function informDeploymentKeepDays(?int $deploymentKeepDays): void
            {
                $this->deploymentKeepDays = $deploymentKeepDays;
            }
            public function informKeepLastDeployment(bool $keepLastDeployment): void
            {
                $this->keepLastDeployment = $keepLastDeployment;
            }
            public function informDeploymentKeepMaxNumber(?int $deploymentKeepMaxNumber): void
            {
                $this->deploymentKeepMaxNumber = $deploymentKeepMaxNumber;
            }
            public function informGitHubWebhookSecret(?string $gitHubWebhookSecret): void
            {
                $this->gitHubWebhookSecret = $gitHubWebhookSecret;
            }
            public function informGitHubWebhookExecutor(?string $gitHubWebhookExecutor): void
            {
                $this->gitHubWebhookUserId = $gitHubWebhookExecutor;
            }
        };
        $this->project->provide($dto);

        if (isset($this->deploymentDataTransformer)) {
            $deployment = $this->projectService->lastDeploymentFrom(new ProjectId($this->project->id()));
            $dto->lastDeployment = $deployment ? $this->deploymentDataTransformer->write($deployment)->read() : null;
        }

        if (isset($this->recipesDataTransformer)) {
            $recipes = $this->projectService->recipesFrom(RecipeIds::of(...$this->project->recipeIds()));
            $dto->recipes = $recipes ? $this->recipesDataTransformer->write($recipes)->read(): [];
        }

        if (isset($this->serverDataTransformer)) {
            $server = $this->projectService->serverFrom(new ServerId($this->project->serverId()));
            $dto->server = $server ? $this->serverDataTransformer->write($server)->read() : null;
        }

        if (isset($this->userDataTransformer)) {
            $user = $dto->gitHubWebhookUserId ? $this->projectService->userFrom(new UserId($dto->gitHubWebhookUserId)) : null;
            $dto->gitHubWebhookUser = $user ? $this->userDataTransformer->write($user)->read() : null;
        }

        $dto->surrogateId = $this->project->surrogateId();
        $dto->createdAt = $this->project->createdAt();
        $dto->updatedAt = $this->project->updatedAt();

        return $dto;
    }

    /**
     * @param DeploymentDataTransformer $deploymentDataTransformer
     * @return self
     */
    public function setDeploymentDataTransformer(DeploymentDataTransformer $deploymentDataTransformer): self
    {
        $this->deploymentDataTransformer = $deploymentDataTransformer;
        return $this;
    }

    /**
     * @param RecipesDataTransformer $recipesDataTransformer
     * @return self
     */
    public function setRecipesDataTransformer(RecipesDataTransformer $recipesDataTransformer): self
    {
        $this->recipesDataTransformer = $recipesDataTransformer;
        return $this;
    }

    /**
     * @param ServerDataTransformer $serverDataTransformer
     * @return self
     */
    public function setServerDataTransformer(ServerDataTransformer $serverDataTransformer): self
    {
        $this->serverDataTransformer = $serverDataTransformer;
        return $this;
    }

    /**
     * @param UserDataTransformer $userDataTransformer
     * @return self
     */
    public function setUserDataTransformer(UserDataTransformer $userDataTransformer): self
    {
        $this->userDataTransformer = $userDataTransformer;
        return $this;
    }
}
