<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Deployer\Domain\Model\DeployerStarted;
use stdClass;
use Webloyer\Domain\Model\Deployment\{
    DeploymentNumber,
    DeploymentRepository,
};
use Webloyer\Domain\Model\Project\{
    ProjectId,
    ProjectRepository,
};

class DeployerStartedListener extends WebloyerEventListener
{
    /** @var DeploymentRepository */
    private $deploymentRepository;
    /** @var ProjectRepository */
    private $projectRepository;

    /**
     * Create the event listener.
     *
     * @param DeploymentRepository $deploymentRepository
     * @param ProjectRepository    $projectRepository
     * @return void
     */
    public function __construct(
        DeploymentRepository $deploymentRepository,
        ProjectRepository $projectRepository
    ) {
        $this->deploymentRepository = $deploymentRepository;
        $this->projectRepository = $projectRepository;
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
        $project = $this->projectRepository->findById(new ProjectId($event->project_id));
        $deployment = $this->deploymentRepository->findById(
            $project,
            new DeploymentNumber($event->number)
        );
        $deployment->changeStatus('running');
        $deployment->changeStartDate($event->start_date);
        $this->deploymentRepository->save($deployment);
    }
}
