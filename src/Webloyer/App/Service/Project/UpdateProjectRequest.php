<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Project;

class UpdateProjectRequest
{
    /** @var string */
    private $id;
    /** @var string */
    private $name;
    /** @var array<int, string> */
    private $recipeIds;
    /** @var string */
    private $serverId;
    /** @var string */
    private $repositoryUrl;
    /** @var string */
    private $stageName;
    /** @var string|null */
    private $deployPath;
    /** @var string|null */
    private $emailNotificationRecipient;
    /** @var int|null */
    private $deploymentKeepDays;
    /** @var bool */
    private $keepLastDeployment;
    /** @var int|null */
    private $deploymentKeepMaxNumber;
    /** @var string|null */
    private $gitHubWebhookSecret;
    /** @var string|null */
    private $gitHubWebhookExecutor;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<int, string>
     */
    public function getRecipeIds(): array
    {
        return $this->recipeIds;
    }

    /**
     * @return string
     */
    public function getServerId(): string
    {
        return $this->serverId;
    }

    /**
     * @return string
     */
    public function getRepositoryUrl(): string
    {
        return $this->repositoryUrl;
    }

    /**
     * @return string
     */
    public function getStageName(): string
    {
        return $this->stageName;
    }

    /**
     * @return string|null
     */
    public function getDeployPath(): ?string
    {
        return $this->deployPath;
    }

    /**
     * @return string|null
     */
    public function getEmailNotificationRecipient(): ?string
    {
        return $this->emailNotificationRecipient;
    }

    /**
     * @return int|null
     */
    public function getDeploymentKeepDays(): ?int
    {
        return $this->deploymentKeepDays;
    }

    /**
     * @return bool
     */
    public function getKeepLastDeployment(): bool
    {
        return $this->keepLastDeployment;
    }

    /**
     * @return int|null
     */
    public function getDeploymentKeepMaxNumber(): ?int
    {
        return $this->deploymentKeepMaxNumber;
    }

    /**
     * @return string|null
     */
    public function getGitHubWebhookSecret(): ?string
    {
        return $this->gitHubWebhookSecret;
    }

    /**
     * @return string|null
     */
    public function getGitHubWebhookExecutor(): ?string
    {
        return $this->gitHubWebhookExecutor;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string ...$recipeIds
     * @return self
     */
    public function setRecipeIds(string ...$recipeIds): self
    {
        $this->recipeIds = $recipeIds;
        return $this;
    }

    /**
     * @param string $serverId
     * @return self
     */
    public function setServerId(string $serverId): self
    {
        $this->serverId = $serverId;
        return $this;
    }

    /**
     * @param string $repositoryUrl
     * @return self
     */
    public function setRepositoryUrl(string $repositoryUrl): self
    {
        $this->repositoryUrl = $repositoryUrl;
        return $this;
    }

    /**
     * @param string $stageName
     * @return self
     */
    public function setStageName(string $stageName): self
    {
        $this->stageName = $stageName;
        return $this;
    }

    /**
     * @param string|null $deployPath
     * @return self
     */
    public function setDeployPath(?string $deployPath): self
    {
        $this->deployPath = $deployPath;
        return $this;
    }

    /**
     * @param string|null $emailNotificationRecipient
     * @return self
     */
    public function setEmailNotificationRecipient(?string $emailNotificationRecipient): self
    {
        $this->emailNotificationRecipient = $emailNotificationRecipient;
        return $this;
    }

    /**
     * @param int|null $deploymentKeepDays
     * @return self
     */
    public function setDeploymentKeepDays(?int $deploymentKeepDays): self
    {
        $this->deploymentKeepDays = $deploymentKeepDays;
        return $this;
    }

    /**
     * @param bool $keepLastDeployment
     * @return self
     */
    public function setKeepLastDeployment(bool $keepLastDeployment): self
    {
        $this->keepLastDeployment = $keepLastDeployment;
        return $this;
    }

    /**
     * @param int|null $deploymentKeepMaxNumber
     * @return self
     */
    public function setDeploymentKeepMaxNumber(?int $deploymentKeepMaxNumber): self
    {
        $this->deploymentKeepMaxNumber = $deploymentKeepMaxNumber;
        return $this;
    }

    /**
     * @param string|null $gitHubWebhookSecret
     * @return self
     */
    public function setGitHubWebhookSecret(?string $gitHubWebhookSecret): self
    {
        $this->gitHubWebhookSecret = $gitHubWebhookSecret;
        return $this;
    }

    /**
     * @param string|null $gitHubWebhookExecutor
     * @return self
     */
    public function setGitHubWebhookExecutor(?string $gitHubWebhookExecutor): self
    {
        $this->gitHubWebhookExecutor = $gitHubWebhookExecutor;
        return $this;
    }
}
