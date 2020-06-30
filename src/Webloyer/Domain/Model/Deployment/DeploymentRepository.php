<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

use Webloyer\Domain\Model\Project\Project;

/**
 * @codeCoverageIgnore
 */
interface DeploymentRepository
{
    /**
     * @param Project $project
     * @return DeploymentNumber
     */
    public function nextId(Project $project): DeploymentNumber;
    /**
     * @param Project $project
     * @return Deployments
     */
    public function findAllByProject(Project $project): Deployments;
    /**
     * @param Project  $project
     * @param int|null $page
     * @param int|null $perPage
     * @return Deployments
     */
    public function findAllByProjectAndPage(Project $project, ?int $page, ?int $perPage): Deployments;
    /**
     * @param Project          $project
     * @param DeploymentNumber $number
     * @return Deployment|null
     */
    public function findById(Project $project, DeploymentNumber $number): ?Deployment;
    /**
     * @param Project $project
     * @return Deployment|null
     */
    public function findLastByProject(Project $project): ?Deployment;
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
