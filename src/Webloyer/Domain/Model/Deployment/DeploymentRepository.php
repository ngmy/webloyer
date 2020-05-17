<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Webloyer\Domain\Model\Project\ProjectId;

/**
 * @codeCoverageIgnore
 */
interface DeploymentRepository
{
    /**
     * @param ProjectId $projectId
     * @return DeploymentNumber
     */
    public function nextId(ProjectId $projectId): DeploymentNumber;
    /**
     * @param ProjectId $projectId
     * @return Deployments
     */
    public function findAllByProjectId(ProjectId $projectId): Deployments;
    /**
     * @param ProjectId $projectId
     * @param int|null $page
     * @param int|null $perPage
     * @return Deployments
     */
    public function findAllByProjectIdAndPage(ProjectId $projectId, ?int $page, ?int $perPage): Deployments;
    /**
     * @param ProjectId        $projectId
     * @param DeploymentNumber $number
     * @return Deployment|null
     */
    public function findById(ProjectId $projectId, DeploymentNumber $number): ?Deployment;
    /**
     * @param Deployment $deployment
     * @return void
     */
    public function remove(Deployment $deployment): void;
    /**
     * @param Deployments $deployments
     * @return void
     */
    public function removeAll(Deployments $deployments): void;
    /**
     * @param Deployment $deployment
     * @return void
     */
    public function save(Deployment $deployment): void;
    /**
     * @param DeploymentSpecification $spec
     * @return Deployments
     */
    public function satisfyingDeployments(DeploymentSpecification $spec): Deployments;
}
