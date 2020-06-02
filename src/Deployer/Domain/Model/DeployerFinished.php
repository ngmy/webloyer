<?php

declare(strict_types=1);

namespace Deployer\Domain\Model;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishableDomainEvent,
};
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\Deployment\DeploymentNumber;

class DeployerFinished implements DomainEvent, PublishableDomainEvent
{
    private $projectId;
    private $number;
    private $log;
    private $status;
    private $finishDate;

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

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function number(): DeploymentNumber
    {
        return $this->number;
    }

    public function log(): string
    {
        return $this->log;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function finishDate(): string
    {
        return $this->finishDate;
    }
}
