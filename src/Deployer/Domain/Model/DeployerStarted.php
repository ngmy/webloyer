<?php

declare(strict_types=1);

namespace Deployer\Domain\Model;

use Common\Domain\Model\Event\DomainEvent;
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\Deployment\DeploymentNumber;

class DeployerStarted implements DomainEvent
{
    /** @var ProjectId */
    private $projectId;
    /** @var DeploymentNumber */
    private $number;
    /** @var string */
    private $startDate;

    /**
     * @param ProjectId        $projectId
     * @param DeploymentNumber $number
     * @param string           $startDate
     * @return void
     */
    public function __construct(
        ProjectId $projectId,
        DeploymentNumber $number,
        string $startDate
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->startDate = $startDate;
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
    public function startDate(): string
    {
        return $this->startDate;
    }
}
