<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use DateTimeImmutable;
use Webloyer\Domain\Model\Project\{
    ProjectId,
    ProjectRepository,
};

class OldDeploymentSpecification implements DeploymentSpecification
{
    /** @var DeploymentRepository */
    private $deploymentRepository;
    /** @var ProjectRepository */
    private $projectRepository;
    /** @var DateTimeImmutable */
    private $currentDate;

    /**
     * @param DeploymentRepository $deploymentRepository
     * @param ProjectRepository    $projectRepository
     * @param DateTimeImmutable    $currentDate
     * @return void
     */
    public function __construct(
        DeploymentRepository $deploymentRepository,
        ProjectRepository $projectRepository,
        DateTimeImmutable $currentDate
    ) {
        $this->deploymentRepository = $deploymentRepository;
        $this->projectRepository = $projectRepository;
        $this->currentDate = $currentDate;
    }

    /**
     * @param Deployment $deployment
     * @return bool
     */
    public function isSatisfiedBy(Deployment $deployment): bool
    {
        if ($deployment->isCompleted()) {
            return false;
        }

        $project = $this->projectRepository->findById(new ProjectId($deployment->projectId()));
        $discardOldDeployment = $project->discardOldDeployment();

        if (!$discardOldDeployment->isEnable()) {
            return false;
        }

        $deployments = $this->deploymentRepository->findAllByProject($project);

        if ($deployments->isEmpty()) {
            return false;
        }

        assert(!is_null($deployments->latest()));
        if ($discardOldDeployment->keepLastDeployment() && $deployments->latest()->equals($deployment)) {
            return false;
        }

        $isSatisfied = false;

        if ($discardOldDeployment->isKeepMaxNumber()) {
            $index = $deployments->indexOf($deployment);
            $isSatisfied = $index >= $discardOldDeployment->keepMaxNumber();
        }

        if ($discardOldDeployment->isKeepDays()) {
            $baseDate = $this->currentDate->modify('-' . $discardOldDeployment->keepDays() . ' days');
            assert(!is_null($deployment->finishDate()));
            $isSatisfied = new DateTimeImmutable($deployment->finishDate()) < $baseDate;
        }

        return $isSatisfied;
    }

    /**
     * TODO add project to param?
     *
     * @param DeploymentRepository $deploymentRepository
     * @return Deployments
     */
    public function satisfyingElementsFrom(DeploymentRepository $deploymentRepository): Deployments
    {
        $projects = $this->projectRepository->findAll();

        $oldDeploymentArray = [];

        foreach ($projects->toArray() as $project) {
            $deployments = $deploymentRepository->findAllByProject($project);

            if ($deployments->isEmpty()) {
                continue;
            }

            $oldDeploymentArray += array_filter($deployments->toArray(), function (Deployment $deployment): bool {
                return $this->isSatisfiedBy($deployment);
            });
        }

        return new Deployments(...$oldDeploymentArray);
    }
}
