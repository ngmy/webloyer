<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\DomainEventPublisher;
use Common\Domain\Model\Identifiable;
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\User\UserId;

class Deployment
{
    use Identifiable;

    /** @var ProjectId */
    private $projectId;
    /** @var DeploymentNumber */
    private $number;
    /** @var DeploymentTask */
    private $task;
    /** @var DeploymentStatus */
    private $status;
    /** @var DeploymentLog */
    private $log;
    /** @var UserId */
    private $executor;

    /**
     * @param ProjectId        $projectId
     * @param DeploymentNumber $number
     * @param DeploymentTask   $task
     * @param DeploymentStatus $status
     * @param DeploymentLog    $log
     * @param UserId           $executor
     * @return void
     */
    public function __construct(
        ProjectId $projectId,
        DeploymentNumber $number,
        DeploymentTask $task,
        DeploymentStatus $status,
        DeploymentLog $log,
        UserId $executor
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->task = $task;
        $this->status = $status;
        $this->log = $log;
        $this->executor = $executor;

        DomainEventPublisher::getInstance()->publish(
            new DeployedEvent($projectId, $number)
        );
    }
}
