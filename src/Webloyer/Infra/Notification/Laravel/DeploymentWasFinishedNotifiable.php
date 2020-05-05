<?php

declare(strict_types=1);

namespace Webloyer\Infra\Notification\Laravel;

use Illuminate\Notifications\Notifiable;
use Webloyer\Domain\Model\Project;

class DeploymentWasFinishedDto implements Project\ProjectInterest
{
    use Notifiable;

    /**
     * @param string $id
     * @return void
     */
    public function informId(string $id): void
    {
    }

    /**
     * @param string $name
     * @return void
     */
    public function informName(string $name): void
    {
    }

    /**
     * @param string ...$recipeIds
     * @return void
     */
    public function informRecipeIds(string ...$recipeIds): void
    {
    }

    /**
     * @param string $serverId
     * @return void
     */
    public function informServerId(string $serverId): void
    {
    }

    /**
     * @param string $repositoryUrl
     * @return void
     */
    public function informRepositoryUrl(string $repositoryUrl): void
    {
    }

    /**
     * @param string $stageName
     * @return void
     */
    public function informStageName(string $stageName): void
    {
    }

    /**
     * @param string|null $deployPath
     * @return void
     */
    public function informDeployPath(?string $deployPath): void
    {
    }

    /**
     * @param string|null $emailNotificationRecipient
     * @return void
     */
    public function informEmailNotificationRecipient(?string $emailNotificationRecipient): void
    {
        $this->email = $emailNotificationRecipient;
    }

    /**
     * @param int|null $deploymentKeepDays
     * @return void
     */
    public function informDeploymentKeepDays(?int $deploymentKeepDays): void
    {
    }

    /**
     * @param bool $keepLastDeployment
     * @return void
     */
    public function informKeepLastDeployment(bool $keepLastDeployment): void
    {
    }

    /**
     * @param int|null $deploymentKeepMaxNumber
     * @return void
     */
    public function informDeploymentKeepMaxNumber(?int $deploymentKeepMaxNumber): void
    {
    }

    /**
     * @param string|null $githubWebhookSecret
     * @return void
     */
    public function informGithubWebhookSecret(?string $githubWebhookSecret): void
    {
    }

    /**
     * @param string|null $githubWebhookExecutor
     * @return void
     */
    public function informGithubWebhookExecutor(?string $githubWebhookExecutor): void
    {
    }
}
