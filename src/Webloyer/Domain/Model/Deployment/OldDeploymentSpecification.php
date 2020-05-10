<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use DateTimeInterface;
use Webloyer\Domain\Model\Project;

class OldDeploymentSpecification implements DeploymentSpecification
{
    public function __construct(
        DeploymentRepository $deploymentRepository,
        Project\ProjectRepository $projectRepository,
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
        $project = $this->projectRepository->findById(new Project\ProjectId($deployment->projectId()));
        $discardOldDeployment = $project->discardOldDeployment();

        if (!$discardOldDeployment->isEnable()) {
            return false;
        }

        $deployments = $this->deploymentRepository->findAllByProjectId(new Project\ProjectId($deployment->projectId()));

        if ($deployments->isEmpty()) {
            return false;
        }

        if ($discardOldDeployment->keepLastDeployment() && $deployment->last()->equals($deployment)) {
            return false;
        }

        $isSatisfied = false;

        if ($discardOldDeployment->isKeepMaxNumber()) {
            $index = $deployments->lastIndexOf($deployment);
            $isSatisfied = $index >= $discardOldDeployment->keepMaxNumber();
        }

        if ($discardOldDeployment->isKeepDays()) {
            $baseDate = $this->currentDate->modify('-' . $discardOldDeployment->keepDays() . ' days');
            $isSatisfied = $deployment->finishDate() < $baseDate;
        }

        return $isSatisfied;
    }

    /**
     * @param DeploymentRepository $deploymentRepository
     * @return Deployments
     */
    public function satisfyingElementsFrom(DeploymentRepository $deploymentRepository): Deployments
    {
        $deployments = $deploymentRepository->findAll();

        $deploymentArray = $deployments->toArray();

        if (empty($deploymentArray)) {
            return Deployments::empty();
        }

        $oldDeploymentArray = array_filter($deploymentArray, function (Deployment $deployment): bool {
            return $this->isSatisfiedBy($deployment);
        });

        return new Deployments(...$oldDeploymentArray);
    }
}
