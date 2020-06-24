<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Deployer\Domain\Model\DeployerStarted;
use stdClass;
use Webloyer\Domain\Model\Deployment\{
    DeploymentNumber,
    DeploymentRepository,
};
use Webloyer\Domain\Model\Project\ProjectId;

class DeployerStartedListener extends WebloyerEventListener
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
     * {@inheritdoc}
     */
    protected function listensTo(string $typeName): bool
    {
        return $typeName == DeployerStarted::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function perform(stdClass $event): void
    {
        $deployment = $this->deploymentRepository->findById(
            new ProjectId($event->project_id),
            new DeploymentNumber($event->number)
        );
        $deployment->changeStatus('running');
        $deployment->changeStartDate($event->start_date);
        $this->deploymentRepository->save($deployment);
    }
}
