<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project;

/**
 * @codeCoverageIgnore
 */
interface ProjectInterest
{
    /**
     * @param string $id
     * @return void
     */
    public function informId(string $id): void;
    /**
     * @param string $name
     * @return void
     */
    public function informName(string $name): void;
    /**
     * @param string ...$recipeIds
     * @return void
     */
    public function informRecipeIds(string ...$recipeIds): void;
    /**
     * @param string $serverId
     * @return void
     */
    public function informServerId(string $serverId): void;
    /**
     * @param string $repositoryUrl
     * @return void
     */
    public function informRepositoryUrl(string $repositoryUrl): void;
    /**
     * @param string $stageName
     * @return void
     */
    public function informStageName(string $stageName): void;
    /**
     * @param string|null $deployPath
     * @return void
     */
    public function informDeployPath(?string $deployPath): void;
    /**
     * @param string|null $emailNotificationRecipient
     * @return void
     */
    public function informEmailNotificationRecipient(?string $emailNotificationRecipient): void;
    /**
     * @param int|null $deploymentKeepDays
     * @return void
     */
    public function informDeploymentKeepDays(?int $deploymentKeepDays): void;
    /**
     * @param bool $keepLastDeployment
     * @return void
     */
    public function informKeepLastDeployment(bool $keepLastDeployment): void;
    /**
     * @param int|null $deploymentKeepMaxNumber
     * @return void
     */
    public function informDeploymentKeepMaxNumber(?int $deploymentKeepMaxNumber): void;
    /**
     * @param string|null $gitHubWebhookSecret
     * @return void
     */
    public function informGitHubWebhookSecret(?string $gitHubWebhookSecret): void;
    /**
     * @param string|null $gitHubWebhookExecutor
     * @return void
     */
    public function informGitHubWebhookExecutor(?string $gitHubWebhookExecutor): void;
}
