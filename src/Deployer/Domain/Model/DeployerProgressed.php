<?php

declare(strict_types=1);

namespace Deployer\Domain\Model;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishableDomainEvent,
};
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Domain\Model\Deployment\DeploymentNumber;

class DeployerProgressed implements DomainEvent, PublishableDomainEvent
{
    private $projectId;
    private $number;
    private $log;

    public function __construct(
        ProjectId $projectId,
        DeploymentNumber $number,
        string $log
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
        $this->log = $log;
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
}
