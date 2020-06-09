<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use DateTimeImmutable;
use DateTimeInterface;
use Webloyer\Domain\Model\Project\{
    ProjectId,
    ProjectRepository,
};

class OldDeploymentSpecification implements DeploymentSpecification
{
    public function __construct(
        DeploymentRepository $deploymentRepository,
        ProjectRepository $projectRepository,
        DateTimeInterface $currentDate
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
        $project = $this->projectRepository->findById(new ProjectId($deployment->projectId()));
        $discardOldDeployment = $project->discardOldDeployment();

        if (!$discardOldDeployment->isEnable()) {
            return false;
        }

        $deployments = $this->deploymentRepository->findAllByProjectId(new ProjectId($deployment->projectId()));

        if ($deployments->isEmpty()) {
            return false;
        }

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
            \Log::debug($baseDate->format('Y-m-d H:i:s'));
            \Log::debug($deployment->finishDate());
            $isSatisfied = new DateTimeImmutable($deployment->finishDate()) < $baseDate;
            \Log::debug($isSatisfied);
        }

        return $isSatisfied;
    }

    /**
     * @param DeploymentRepository $deploymentRepository
     * @return Deployments
     */
    public function satisfyingElementsFrom(DeploymentRepository $deploymentRepository): Deployments
    {
        $projects = $this->projectRepository->findAll();

        $oldDeploymentArray = [];

        foreach ($projects->toArray() as $project) {
            $deployments = $deploymentRepository->findAllByProjectId(new ProjectId($project->id()));

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
