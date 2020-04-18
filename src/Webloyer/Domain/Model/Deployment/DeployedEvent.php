<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\{
    DomainEvent,
    PublishableDomainEvent,
};

class DeployedEvent implements DomainEvent, PublishableDomainEvent
{
    /** @var DeploymentId */
    private $id;

    /**
     * @param DeploymentId $id;
     * @return void
     */
    public function __construct(DeploymentId $id)
    {
        $this->id = $id;
    }
}
