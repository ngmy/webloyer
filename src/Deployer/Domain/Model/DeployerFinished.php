<?php

declare(strict_types=1);

namespace Deployer\Domain\Model;

use Common\Domain\Model\Event\DomainEvent;
use Webloyer\Domain\Model\Deployment\DeploymentNumber;
use Webloyer\Domain\Model\Project\ProjectId;

class DeployerFinished implements DomainEvent
{
    /** @var ProjectId */
    private $projectId;
    /** @var DeploymentNumber */
    private $number;
    /** @var string */
    private $log;
    /** @var int */
    private $status;
    /** @var string */
    private $finishDate;

    /**
     * @param ProjectId        $projectId
     * @param DeploymentNumber $number
     * @param string           $log
     * @param int              $status
     * @param string           $finishDate
     */
    public function __construct(
        ProjectId $projectId,
        DeploymentNumber $number,
        string $log,
        int $status,
        string $finishDate
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->log = $log;
        $this->status = $status;
        $this->finishDate = $finishDate;
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

    /**
     * @return int
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function finishDate(): string
    {
        return $this->finishDate;
    }
}
