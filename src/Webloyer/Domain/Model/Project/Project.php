<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project;

use Common\Domain\Model\Identity\Identifiable;
use Common\Domain\Model\Timestamp\Timestampable;
use Webloyer\Domain\Model\Project\DiscardOldDeployment\DiscardOldDeployment;
use Webloyer\Domain\Model\Project\Notification\Email\EmailNotification;
use Webloyer\Domain\Model\Project\ServerOverride\ServerOverride;
use Webloyer\Domain\Model\Project\Webhook\GitHub\GitHubWebhook;
use Webloyer\Domain\Model\Recipe\RecipeIds;
use Webloyer\Domain\Model\Server\{
    NullServerId,
    ServerId,
};

class Project
{
    use Identifiable;
    use Timestampable;

    /** @var ProjectId */
    private $id;
    /** @var ProjectName */
    private $name;
    /** @var RecipeIds */
    private $recipeIds;
    /** @var ServerId */
    private $serverId;
    /** @var RepositoryUrl */
    private $repositoryUrl;
    /** @var StageName */
    private $stageName;
    /** @var ServerOverride */
    private $serverOverride;
    /** @var EmailNotification */
    private $emailNotification;
    /** @var DiscardOldDeployment */
    private $discardOldDeployment;
    /** @var GitHubWebhook */
    private $gitHubWebhook;

    /**
     * @param string       $id
     * @param string       $name
     * @param list<string> $recipeIds
     * @param string|null  $serverId
     * @param string       $repositoryUrl
     * @param string       $stageName
     * @param string|null  $deployPath
     * @param string|null  $emailNotificationRecipient
     * @param int|null     $deploymentKeepDays
     * @param bool         $keepLastDeployment
     * @param int|null     $deploymentKeepMaxNumber
     * @param string|null  $gitHubWebhookSecret
     * @param string|null  $gitHubWebhookExecutor
     * @return self
     */
    public static function of(
        string $id,
        string $name,
        array $recipeIds,
        ?string $serverId,
        string $repositoryUrl,
        string $stageName,
        ?string $deployPath,
        ?string $emailNotificationRecipient,
        ?int $deploymentKeepDays,
        bool $keepLastDeployment,
        ?int $deploymentKeepMaxNumber,
        ?string $gitHubWebhookSecret,
        ?string $gitHubWebhookExecutor
    ) {
        return new self(
            new ProjectId($id),
            new ProjectName($name),
            RecipeIds::of(...$recipeIds),
            isset($serverId) ? new ServerId($serverId) : NullServerId::getInstance(),
            new RepositoryUrl($repositoryUrl),
            new StageName($stageName),
            ServerOverride::of($deployPath),
            EmailNotification::of($emailNotificationRecipient),
            DiscardOldDeployment::of(
                $deploymentKeepDays,
                $keepLastDeployment,
                $deploymentKeepMaxNumber
            ),
            GitHubWebhook::of(
                $gitHubWebhookSecret,
                $gitHubWebhookExecutor
            )
        );
    }

    /**
     * @param ProjectId            $id
     * @param ProjectName          $name
     * @param RecipeIds            $recipeIds
     * @param ServerId             $serverId
     * @param ServerOverride       $serverOverride
     * @param EmailNotification    $emailNotification
     * @param DiscardOldDeployment $discardOldDeployment
     * @param GitHubWebhook        $gitHubWebhook
     * @return void
     */
    public function __construct(
        ProjectId $id,
        ProjectName $name,
        RecipeIds $recipeIds,
        ServerId $serverId,
        RepositoryUrl $repositoryUrl,
        StageName $stageName,
        ServerOverride $serverOverride,
        EmailNotification $emailNotification,
        DiscardOldDeployment $discardOldDeployment,
        GitHubWebhook $gitHubWebhook
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->recipeIds = $recipeIds;
        $this->serverId = $serverId;
        $this->repositoryUrl = $repositoryUrl;
        $this->stageName = $stageName;
        $this->serverOverride = $serverOverride;
        $this->emailNotification = $emailNotification;
        $this->discardOldDeployment = $discardOldDeployment;
        $this->gitHubWebhook = $gitHubWebhook;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id->value();
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name->value();
    }

    /**
     * @return list<string>
     */
    public function recipeIds(): array
    {
        return $this->recipeIds->toArray();
    }

    /**
     * @return string
     */
    public function serverId(): string
    {
        return $this->serverId->value();
    }

    /**
     * @return string
     */
    public function repositoryUrl(): string
    {
        return $this->repositoryUrl->value();
    }

    /**
     * @return string
     */
    public function stageName(): string
    {
        return $this->stageName->value();
    }

    public function discardOldDeployment(): DiscardOldDeployment
    {
        return $this->discardOldDeployment;
    }

    public function emailNotification(): EmailNotification
    {
        return $this->emailNotification;
    }

    public function serverOverride(): ServerOverride
    {
        return $this->serverOverride;
    }

    /**
     * @param string $name
     * @return self
     */
    public function changeName(string $name): self
    {
        $this->name = new ProjectName($name);
        return $this;
    }

    /**
     * @param string ...$recipeIds
     * @return self
     */
    public function changeRecipes(string ...$recipeIds): self
    {
        $this->recipeIds = RecipeIds::of(...$recipeIds);
        return $this;
    }

    /**
     * @param string $serverId
     * @return self
     */
    public function changeServer(string $serverId): self
    {
        $this->serverId = new ServerId($serverId);
        return $this;
    }

    /**
     * @param string $repositoryUrl
     * @return self
     */
    public function changeRepositoryUrl(string $repositoryUrl): self
    {
        $this->repositoryUrl = new RepositoryUrl($repositoryUrl);
        return $this;
    }

    /**
     * @param string $stageName
     * @return self
     */
    public function changeStageName(string $stageName): self
    {
        $this->stageName = new StageName($stageName);
        return $this;
    }

    public function changeDeployPath(?string $deployPath): self
    {
        $this->serverOverride = ServerOverride::of($deployPath);
        return $this;
    }

    public function changeEmailNotificationRecipient(?string $emailNotificationRecipient): self
    {
        $this->emailNotification = EmailNotification::of($emailNotificationRecipient);
        return $this;
    }

    public function changeDeploymentKeepDays(?int $deploymentKeepDays): self
    {
        $this->discardOldDeployment = DiscardOldDeployment::of(
            $deploymentKeepDays,
            $this->discardOldDeployment->keepLastDeployment(),
            $this->discardOldDeployment->keepMaxNumber(),
        );
        return $this;
    }

    public function changeKeepLastDeployment(bool $keepLastDeployment): self
    {
        $this->discardOldDeployment = DiscardOldDeployment::of(
            $this->discardOldDeployment->keepDays(),
            $keepLastDeployment,
            $this->discardOldDeployment->keepMaxNumber(),
        );
        return $this;
    }

    public function changeDeploymentKeepMaxNumber(?int $deploymentKeepMaxNumber): self
    {
        $this->discardOldDeployment = DiscardOldDeployment::of(
            $this->discardOldDeployment->keepDays(),
            $this->discardOldDeployment->keepLastDeployment(),
            $deploymentKeepMaxNumber
        );
        return $this;
    }

    public function changeGitHubWebhookSecret(?string $gitHubWebhookSecret): self
    {
        $this->gitHubWebhook = GitHubWebhook::of(
            $gitHubWebhookSecret,
            $this->gitHubWebhook->executor()
        );
        return $this;
    }

    public function changeGitHubWebhookExecutor(?string $gitHubWebhookExecutor): self
    {
        $this->gitHubWebhook = GitHubWebhook::of(
            $this->gitHubWebhook->secret(),
            $gitHubWebhookExecutor
        );
        return $this;
    }

    /**
     * @param ProjectInterest $interest
     * @return void
     */
    public function provide(ProjectInterest $interest): void
    {
        $interest->informId($this->id());
        $interest->informName($this->name());
        $interest->informRecipeIds(...$this->recipeIds());
        $interest->informServerId($this->serverId());
        $interest->informRepositoryUrl($this->repositoryUrl());
        $interest->informStageName($this->stageName());
        $this->serverOverride->provide($interest);
        $this->emailNotification->provide($interest);
        $this->discardOldDeployment->provide($interest);
        $this->gitHubWebhook->provide($interest);
    }

    /**
     * @param mixed $object
     * @return bool
     */
    public function equals($object): bool
    {
        $equalObjects = false;

        if ($object instanceof self) {
            $equalObjects = $object->id == $this->id;
        }

        return $equalObjects;
    }
}
