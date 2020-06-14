<?php

declare(strict_types=1);

namespace Deployer\Domain\Model;

use Common\Domain\Model\Event\DomainEvent;
use Webloyer\Domain\Model\Deployment\DeploymentNumber;
use Webloyer\Domain\Model\Project\ProjectId;

class DeployerProgressed implements DomainEvent
{
    /** @var ProjectId */
    private $projectId;
    /** @var DeploymentNumber */
    private $number;
    /** @var string */
    private $log;

    /**
     * @param  ProjectId        $projectId
     * @param  DeploymentNumber $number
     * @param  string           $log
     * @return void
     */
    public function __construct(
        ProjectId $projectId,
        DeploymentNumber $number,
        string $log
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->log = $log;
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
     * @return string
     */
    public function log(): string
    {
        return $this->log;
    }
}
