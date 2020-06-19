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
    /** @var Project */
    private $project;
    /** @var ProjectService */
    private $projectService;
    /** @var DeploymentDataTransformer */
    private $deploymentDataTransformer;
    /** @var RecipesDataTransformer */
    private $recipesDataTransformer;
    /** @var ServerDataTransformer */
    private $serverDataTransformer;
    /** @var UserDataTransformer */
    private $userDataTransformer;

    /**
     * @param ProjectService $projectService
     * @return void
     */
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
            /** @var string */
            public $id;
            /** @var string */
            public $name;
            /** @var list<string> */
            public $recipeIds;
            /** @var string */
            public $serverId;
            /** @var string */
            public $repositoryUrl;
            /** @var string */
            public $stageName;
            /** @var string|null */
            public $deployPath;
            /** @var string|null */
            public $emailNotificationRecipient;
            /** @var int|null */
            public $deploymentKeepDays;
            /** @var bool */
            public $keepLastDeployment;
            /** @var int|null */
            public $deploymentKeepMaxNumber;
            /** @var string|null */
            public $gitHubWebhookSecret;
            /** @var string|null */
            public $gitHubWebhookUserId;
            /** @var object|null */
            public $lastDeployment;
            /** @var list<object>|null */
            public $recipes;
            /** @var object|null */
            public $server;
            /** @var object|null */
            public $gitHubWebhookUser;
            /** @var int */
            public $surrogateId;
            /** @var string */
            public $createdAt;
            /** @var string */
            public $updatedAt;
            /**
             * @param string $id
             * @return void
             */
            public function informId(string $id): void
            {
                $this->id = $id;
            }
            /**
             * @param string $name
             * @return void
             */
            public function informName(string $name): void
            {
                $this->name = $name;
            }
            /**
             * @param string ...$recipeIds
             * @return void
             */
            public function informRecipeIds(string ...$recipeIds): void
            {
                $this->recipeIds = $recipeIds;
            }
            /**
             * @param string $serverId
             * @return void
             */
            public function informServerId(string $serverId): void
            {
                $this->serverId = $serverId;
            }
            /**
             * @param string $repositoryUrl
             * @return void
             */
            public function informRepositoryUrl(string $repositoryUrl): void
            {
                $this->repositoryUrl = $repositoryUrl;
            }
            /**
             * @param string $stageName
             * @return void
             */
            public function informStageName(string $stageName): void
            {
                $this->stageName = $stageName;
            }
            /**
             * @param string|null $deployPath
             * @return void
             */
            public function informDeployPath(?string $deployPath): void
            {
                $this->deployPath = $deployPath;
            }
            /**
             * @param string|null $emailNotificationRecipient
             * @return void
             */
            public function informEmailNotificationRecipient(?string $emailNotificationRecipient): void
            {
                $this->emailNotificationRecipient = $emailNotificationRecipient;
            }
            /**
             * @param int|null $deploymentKeepDays
             * @return void
             */
            public function informDeploymentKeepDays(?int $deploymentKeepDays): void
            {
                $this->deploymentKeepDays = $deploymentKeepDays;
            }
            /**
             * @param bool $keepLastDeployment
             * @return void
             */
            public function informKeepLastDeployment(bool $keepLastDeployment): void
            {
                $this->keepLastDeployment = $keepLastDeployment;
            }
            /**
             * @param int|null $deploymentKeepMaxNumber
             * @return void
             */
            public function informDeploymentKeepMaxNumber(?int $deploymentKeepMaxNumber): void
            {
                $this->deploymentKeepMaxNumber = $deploymentKeepMaxNumber;
            }
            /**
             * @param string|null $gitHubWebhookSecret
             * @return void
             */
            public function informGitHubWebhookSecret(?string $gitHubWebhookSecret): void
            {
                $this->gitHubWebhookSecret = $gitHubWebhookSecret;
            }
            /**
             * @param string|null $gitHubWebhookExecutor
             * @return void
             */
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
            $dto->recipes = $this->recipesDataTransformer->write($recipes)->read();
        }

        if (isset($this->serverDataTransformer)) {
            $server = $this->projectService->serverFrom(new ServerId($this->project->serverId()));
            $dto->server = $this->serverDataTransformer->write($server)->read();
        }

        if (isset($this->userDataTransformer)) {
            $user = $dto->gitHubWebhookUserId ? $this->projectService->userFrom(new UserId($dto->gitHubWebhookUserId)) : null;
            $dto->gitHubWebhookUser = $user ? $this->userDataTransformer->write($user)->read() : null;
        }

        $dto->surrogateId = $this->project->surrogateId();
        assert(!is_null($this->project->createdAt()));
        $dto->createdAt = $this->project->createdAt();
        assert(!is_null($this->project->updatedAt()));
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
