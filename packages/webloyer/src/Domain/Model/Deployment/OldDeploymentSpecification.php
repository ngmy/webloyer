<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use DateTimeImmutable;
use Ngmy\Webloyer\Common\QueryObject\Direction;
use Ngmy\Webloyer\Common\QueryObject\Order;
use Ngmy\Webloyer\Common\QueryObject\QueryObject;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentCriteria;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\AbstractDeploymentSpecification;

class OldDeploymentSpecification extends AbstractDeploymentSpecification
{
    private $project;

    private $currentDate;

    public function __construct(Project $project, DateTimeImmutable $currentDate)
    {
        $this->project = $project;
        $this->currentDate = $currentDate;
    }

    /**
     * Get elements that satisfy the specification.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentRepositoryInterface $deploymentRepository
     * @return array
     */
    public function satisfyingElementsFrom(DeploymentRepositoryInterface $deploymentRepository)
    {
        $criteria = new DeploymentCriteria($this->project->projectId()->id());
        $order = new Order('deployments.created_at', Direction::desc());
        $queryObject = new QueryObject();
        $queryObject->setCriteria($criteria)
            ->addOrder($order);
        $deployments = $deploymentRepository->deployments($queryObject)->all();

        if (empty($deployments)) {
            return [];
        }

        $lastDeployment = $deployments[0];

        $oldDeployments = [];
        $pastDaysToKeepDeployments = [];
        $pastNumToKeepDeployments = [];

        $daysToKeepDeployments = $this->project->daysToKeepDeployments();
        if (!is_null($daysToKeepDeployments)) {
            $keepLastDeployment = $this->project->keepLastDeployment();
            $baseDate = $this->currentDate->modify('-' . $daysToKeepDeployments . ' days');
            $pastDaysToKeepDeployments = array_filter($deployments, function ($deployment, $index) use ($baseDate, $keepLastDeployment) {
                return ($deployment->createdAt() < $baseDate) && !($keepLastDeployment && $index == 0);
            }, ARRAY_FILTER_USE_BOTH);
        }

        $maxNumberOfDeploymentsToKeep = $this->project->maxNumberOfDeploymentsToKeep();
        if (!is_null($maxNumberOfDeploymentsToKeep)) {
            $baseNumber = $lastDeployment->deploymentId()->id() - $maxNumberOfDeploymentsToKeep + 1;
            $pastNumToKeepDeployments = array_filter($deployments, function ($deployment) use ($baseNumber) {
                return $deployment->deploymentId()->id() < $baseNumber;
            });
        }

        $oldDeployments = array_merge($pastDaysToKeepDeployments, $pastNumToKeepDeployments);
        array_unique($oldDeployments, SORT_REGULAR);

        return $oldDeployments;
    }
}
