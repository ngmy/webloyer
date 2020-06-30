<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Listeners;

use Deployer\Domain\Model\DeployerProgressed;
use stdClass;
use Webloyer\Domain\Model\Deployment\{
    DeploymentNumber,
    DeploymentRepository,
};
use Webloyer\Domain\Model\Project\{
    ProjectId,
    ProjectRepository,
};

class DeployerProgressedListener extends WebloyerEventListener
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
        return $typeName == DeployerProgressed::class;
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
        $deployment->appendLog($event->log);
        $this->deploymentRepository->save($deployment);
    }
}
