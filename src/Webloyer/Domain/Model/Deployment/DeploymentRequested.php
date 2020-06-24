<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishNowDomainEvent,
};
use Webloyer\Domain\Model\Project\Notification\Email\EmailNotification;
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\Project\RepositoryUrl;
use Webloyer\Domain\Model\Project\StageName;
use Webloyer\Domain\Model\Project\ServerOverride\ServerOverride;
use Webloyer\Domain\Model\Recipe\RecipeBodies;
use Webloyer\Domain\Model\Server\ServerBody;
use Webloyer\Domain\Model\User\UserEmail;

class DeploymentRequested implements DomainEvent, PublishNowDomainEvent
{
    /** @var ProjectId */
    private $projectId;
    /** @var DeploymentNumber */
    private $number;
    /** @var DeploymentTask */
    private $task;
    /** @var RepositoryUrl */
    private $repositoryUrl;
    /** @var StageName */
    private $stageName;
    /** @var ServerOverride */
    private $serverOverride;
    /** @var EmailNotification */
    private $emailNotification;
    /** @var RecipeBodies */
    private $recipeBodies;
    /** @var ServerBody */
    private $serverBody;
    /** @var UserEmail */
    private $userEmail;

    /**
     * @param ProjectId         $projectId
     * @param DeploymentNumber  $number
     * @param DeploymentTask    $task
     * @param RepositoryUrl     $repositoryUrl
     * @param StageName         $stageName
     * @param ServerOverride    $serverOverride
     * @param EmailNotification $emailNotification
     * @param RecipeBodies      $recipeBodies
     * @param ServerBody        $serverBody
     * @param UserEmail         $userEmail
     * @return void
     */
    public function __construct(
        ProjectId $projectId,
        DeploymentNumber $number,
        DeploymentTask $task,
        RepositoryUrl $repositoryUrl,
        StageName $stageName,
        ServerOverride $serverOverride,
        EmailNotification $emailNotification,
        RecipeBodies $recipeBodies,
        ServerBody $serverBody,
        UserEmail $userEmail
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->task = $task;
        $this->repositoryUrl = $repositoryUrl;
        $this->stageName = $stageName;
        $this->serverOverride = $serverOverride;
        $this->emailNotification = $emailNotification;
        $this->recipeBodies = $recipeBodies;
        $this->serverBody = $serverBody;
        $this->userEmail = $userEmail;
    }

    /**
     * @return ProjectId
     */
    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    /**
     * @return DeploymentNumber
     */
    public function number(): DeploymentNumber
    {
        return $this->number;
    }

    /**
     * @return DeploymentTask
     */
    public function task(): DeploymentTask
    {
        return $this->task;
    }

    /**
     * @return RepositoryUrl
     */
    public function repositoryUrl(): RepositoryUrl
    {
        return $this->repositoryUrl;
    }

    /**
     * @return StageName
     */
    public function stageName(): StageName
    {
        return $this->stageName;
    }

    /**
     * @return ServerOverride
     */
    public function serverOverride(): ServerOverride
    {
        return $this->serverOverride;
    }

    /**
     * @return RecipeBodies
     */
    public function recipeBodies(): RecipeBodies
    {
        return $this->recipeBodies;
    }

    /**
     * @return ServerBody
     */
    public function serverBody(): ServerBody
    {
        return $this->serverBody;
    }

    /**
     * @return UserEmail
     */
    public function userEmail(): UserEmail
    {
        return $this->userEmail;
    }

    /**
     * @return EmailNotification
     */
    public function emailNotification(): EmailNotification
    {
        return $this->emailNotification;
    }
}
