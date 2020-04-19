<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishableDomainEvent,
};
use Webloyer\Domain\Model\Project\ProjectId;

class DeployedEvent implements DomainEvent, PublishableDomainEvent
{
    /** @var ProjectId */
    private $projectId;
    /** @var DeploymentNumber */
    private $number;

    /**
     * @param ProjectId $projectId
     * @param DeploymentNumber $number
     * @return void
     */
    public function __construct(
        ProjectId $projectId,
        DeploymentNumber $number
    ) {
        $this->projectId = $projectId;
        $this->number = $number;
    }
}
