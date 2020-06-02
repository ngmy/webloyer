<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishableDomainEvent,
};
use Webloyer\Domain\Model\Project\Notification\Email\EmailNotification;
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\Project\RepositoryUrl;
use Webloyer\Domain\Model\Project\StageName;
use Webloyer\Domain\Model\Project\ServerOverride\ServerOverride;
use Webloyer\Domain\Model\Recipe\RecipeBodies;
use Webloyer\Domain\Model\Server\ServerBody;
use Webloyer\Domain\Model\User\UserEmail;

class DeploymentRequested implements DomainEvent, PublishableDomainEvent
{
    private $projectId;
    private $number;
    private $task;
    private $repositoryUrl;
    private $stageName;
    private $serverOverride;
    private $emailNotification;
    private $recipeBodies;
    private $serverBody;
    private $userEmail;

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

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function number(): DeploymentNumber
    {
        return $this->number;
    }

    public function task(): DeploymentTask
    {
        return $this->task;
    }

    public function repositoryUrl(): RepositoryUrl
    {
        return $this->repositoryUrl;
    }

    public function stageName(): StageName
    {
        return $this->stageName;
    }

    public function serverOverride(): ServerOverride
    {
        return $this->serverOverride;
    }

    public function recipeBodies(): RecipeBodies
    {
        return $this->recipeBodies;
    }

    public function serverBody(): ServerBody
    {
        return $this->serverBody;
    }

    public function userEmail(): UserEmail
    {
        return $this->userEmail;
    }

    public function emailNotification(): EmailNotification
    {
        return $this->emailNotification;
    }
}
