<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Common\Domain\Model\Event\DomainEventPublisher;
use Common\Domain\Model\Identifiable;

class Deployment
{
    use Identifiable;

    /** @var DeploymentId */
    private $id;
    /** @var DeploymentTask */
    private $task;

    /**
     * @param DeploymentId $id
     * @param DeploymentTask $task
     * @return void
     */
    public function __construct(
        DeploymentId $id,
        DeploymentTask $task
    ) {
        $this->id = $id;
        $this->task = $task;

        DomainEventPublisher::getInstance()->publish(new DeployedEvent($id));
    }
}
