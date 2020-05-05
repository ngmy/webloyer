<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project;

use Common\Domain\Model\Identifiable;
use Webloyer\Domain\Model\Deployment;
use Webloyer\Domain\Model\Recipe;
use Webloyer\Domain\Model\Server;

class Project
{
    use Identifiable;

    /** @var ProjectId */
    private $id;
    /** @var ProjectName */
    private $name;
    /** @var Recipe\RecipeIds */
    private $recipeIds;
    /** @var Server\ServerId */
    private $serverId;
    /** @var RepositoryUrl */
    private $repositoryUrl;
    /** @var StageName */
    private $stageName;
    /** @var ServerOverride\ServerOverride */
    private $serverOverride;
    /** @var Notification\Email\EmailNotification */
    private $emailNotification;
    /** @var DiscardOldDeployment\DiscardOldDeployment */
    private $discardOldDeployment;
    /** @var Webhook\Github\GithubWebhook */
    private $githubWebhook;

    /**
     * @param string             $id
     * @param string             $name
     * @param array<int, string> $recipeIds
     * @param string             $serverId
     * @param string             $repositoryUrl
     * @param string             $stageName
     * @param string|null        $deployPath
     * @param string|null        $emailNotificationRecipient
     * @param int|null           $deploymentKeepDays
     * @param bool               $keepLastDeployment
     * @param int|null           $deploymentKeepMaxNumber
     * @param string|null        $githubWebhookSecret
     * @param string|null        $githubWebhookExecutor
     * @return self
     */
    public static function of(
        string $id,
        string $name,
        array $recipeIds,
        string $serverId,
        string $repositoryUrl,
        string $stageName,
        ?string $deployPath,
        ?string $emailNotificationRecipient,
        ?int $deploymentKeepDays,
        bool $keepLastDeployment,
        ?int $deploymentKeepMaxNumber,
        ?string $githubWebhookSecret,
        ?string $githubWebhookExecutor
    ) {
        return new self(
            new ProjectId($id),
            new ProjectName($name),
            Recipe\RecipeIds::of(...$recipeIds),
            new Server\ServerId($serverId),
            new RepositoryUrl($repositoryUrl),
            new StageName($stageName),
            ServerOverride\ServerOverride::of($deployPath),
            Notification\Email\EmailNotification::of($emailNotificationRecipient),
            DiscardOldDeployment\DiscardOldDeployment::of(
                $deploymentKeepDays,
                $keepLastDeployment,
                $deploymentKeepMaxNumber
            ),
            Webhook\Github\GithubWebhook::of(
                $githubWebhookSecret,
                $githubWebhookExecutor
            )
        );
    }

    /**
     * @param ProjectId                                 $id
     * @param ProjectName                               $name
     * @param Recipe\RecipeIds                          $recipeIds
     * @param Server\ServerId                           $serverId
     * @param ServerOverride\ServerOverride             $serverOverride
     * @param Notification\Email\EmailNotification      $emailNotification
     * @param DiscardOldDeployment\DiscardOldDeployment $discardOldDeployment
     * @param Webhook\Github\GithubWebhook              $githubWebhook
     * @return void
     */
    public function __construct(
        ProjectId $id,
        ProjectName $name,
        Recipe\RecipeIds $recipeIds,
        Server\ServerId $serverId,
        RepositoryUrl $repositoryUrl,
        StageName $stageName,
        ServerOverride\ServerOverride $serverOverride,
        Notification\Email\EmailNotification $emailNotification,
        DiscardOldDeployment\DiscardOldDeployment $discardOldDeployment,
        Webhook\Github\GithubWebhook $githubWebhook
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
        $this->githubWebhook = $githubWebhook;
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
     * @return array<int, string>
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

    public function discardOldDeployment(): DiscardOldDeployment\DiscardOldDeployment
    {
        return $this->discardOldDeployment;
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
        $this->recipeIds = Recipe\RecipeIds::of(...$recipeIds);
        return $this;
    }

    /**
     * @param string $serverId
     * @return self
     */
    public function changeServer(string $serverId): self
    {
        $this->serverId = new Server\ServerId($serverId);
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
        $this->serverOverride = ServerOverride\ServerOverride::of($deployPath);
        return $this;
    }

    public function changeEmailNotificationRecipient(?string $emailNotificationRecipient): self
    {
        $this->emailNotification = Notification\Email\EmailNotification::of($emailNotificationRecipient);
        return $this;
    }

    public function changeDeploymentKeepDays(?int $deploymentKeepDays): self
    {
        $this->discardOldDeployment = DiscardOldDeployment\DiscardOldDeployment::of(
            $deploymentKeepDays,
            $this->discardOldDeployment->keepLastDeployment(),
            $this->discardOldDeployment->keepMaxNumber(),
        );
        return $this;
    }

    public function changeKeepLastDeployment(bool $keepLastDeployment): self
    {
        $this->discardOldDeployment = DiscardOldDeployment\DiscardOldDeployment::of(
            $this->discardOldDeployment->keepDays(),
            $keepLastDeployment,
            $this->discardOldDeployment->keepMaxNumber(),
        );
        return $this;
    }

    public function changeDeploymentKeepMaxNumber(?int $deploymentKeepMaxNumber): self
    {
        $this->discardOldDeployment = DiscardOldDeployment\DiscardOldDeployment::of(
            $this->discardOldDeployment->keepDays(),
            $this->discardOldDeployment->keepLastDeployment(),
            $deploymentKeepMaxNumber
        );
        return $this;
    }

    public function changeGithubWebhookSecret(?string $githubWebhookSecret): self
    {
        $this->githubWebhook = Webhook\Github\GithubWebhook::of(
            $githubWebhookSecret,
            $this->githubWebhook->executor()
        );
        return $this;
    }

    public function changeGithubWebhookExecutor(?string $githubWebhookExecutor): self
    {
        $this->githubWebhook = Webhook\Github\GithubWebhook::of(
            $this->githubWebhook->secret(),
            $githubWebhookExecutor
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
        $this->githubWebhook->provide($interest);
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
