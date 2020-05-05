<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishableDomainEvent,
};
use Webloyer\Domain\Model\Project\Project;
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\Recipe\Recipes;
use Webloyer\Domain\Model\Server\Server;
use Webloyer\Domain\Model\User\User;

class DeploymentWasFinishedEvent implements DomainEvent, PublishableDomainEvent
{

    /**
     * @param ProjectId $projectId
     * @param DeploymentNumber $number
     * @param DeploymentTask $task
     * @return void
     */
    public function __construct(
        Deployment $deployment,
        Project $project,
        Recipes $recipes,
        Server $server,
        User $executor
    ) {
        $this->deployment = $deployment;
        $this->project = $project;
        $this->recipes = $recipes;
        $this->server = $server;
        $this->executor = $executor;
    }

    public function deployment(): Deployment
    {
        return $this->deployment;
    }

    public function project(): Project
    {
        return $this->project;
    }

    public function recipes(): Recipes
    {
        return $this->recipes;
    }

    public function server(): Server
    {
        return $this->server;
    }

    public function executor(): Executor
    {
        return $this->executor;
    }
}
