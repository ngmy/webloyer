<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Deployer\Domain\Model\DeployerFinished;
use stdClass;
use Webloyer\Domain\Model\Deployment\{
    DeploymentNumber,
    DeploymentRepository,
};
use Webloyer\Domain\Model\Project\ProjectId;

class DeployerFinishedListener extends WebloyerEventListener
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
        return $typeName == DeployerFinished::class;
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
        $deployment->changeLog($event->log)
            ->changeStatus($event->status == 0 ? 'succeeded' : 'failed')
            ->changeFinishDate($event->finish_date)
            ->complete();
        $this->deploymentRepository->save($deployment);
    }
}
