<?php

declare(strict_types=1);

namespace Deployer\Domain\Model;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishableDomainEvent,
};
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\Deployment\DeploymentNumber;

class DeployerStarted implements DomainEvent, PublishableDomainEvent
{
    private $projectId;
    private $number;
    private $startDate;

    public function __construct(
        ProjectId $projectId,
        DeploymentNumber $number,
        string $startDate
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->startDate = $startDate;
    }

    public function projectId(): ProjectId
    {
        return $this->projectId;
    }

    public function number(): DeploymentNumber
    {
        return $this->number;
    }

    public function startDate(): string
    {
        return $this->startDate;
    }
}
