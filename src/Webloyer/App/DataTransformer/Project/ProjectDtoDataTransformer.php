<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Project;

use Webloyer\Domain\Model\Project\{
    Project,
    ProjectInterest,
};

class ProjectDtoDataTransformer implements ProjectDataTransformer
{
    private $project;

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
                $this->uuid = $id;
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
            public function informGithubWebhookSecret(?string $githubWebhookSecret): void
            {
                $this->githubWebhookSecret = $githubWebhookSecret;
            }
            public function informGithubWebhookExecutor(?string $githubWebhookExecutor): void
            {
                $this->githubWebhookUserId = $githubWebhookExecutor;
            }
        };
        $this->project->provide($dto);

        $dto->surrogateId = $this->project->surrogateId();
        $dto->createdAt = $this->project->createdAt();
        $dto->updatedAt = $this->project->updatedAt();

        return $dto;
    }
}
