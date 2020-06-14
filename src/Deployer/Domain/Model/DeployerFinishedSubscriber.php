<?php

declare(strict_types=1);

namespace Deployer\Domain\Model;

use Common\Domain\Model\Event\{
    DomainEvent,
    DomainEventSubscriber,
};
use Webloyer\Domain\Model\Deployment\DeploymentRepository;

class DeployerFinishedSubscriber implements DomainEventSubscriber
{
    /** @var DeploymentRepository */
    private $deploymentRepository;

    /**
     * Create the event listener.
     *
     * @param DeploymentRepository $deploymentRepository
     * @return void
     */
    public function __construct(DeploymentRepository $deploymentRepository)
    {
        $this->deploymentRepository = $deploymentRepository;
    }

    /**
     * @param DeployerFinished $domainEvent
     * @return void
     */
    public function handle(DomainEvent $domainEvent): void
    {
        $deployment = $this->deploymentRepository->findById($domainEvent->projectId(), $domainEvent->number());
        $deployment->changeLog($domainEvent->log())
            ->changeStatus($domainEvent->status() == 0 ? 'succeeded' : 'failed')
            ->changeFinishDate($domainEvent->finishDate())
            ->complete();
        $this->deploymentRepository->save($deployment);
    }

    /**
     * @param DomainEvent $domainEvent
     * @return bool
     */
    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return $domainEvent instanceof DeployerFinished;
    }
}
