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
     * @return DeploymentNumber
     */
    public function nextId(): DeploymentNumber;
    /**
     * @return Deployments
     */
    public function findAll(): Deployments;
    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Deployments
     */
    public function findAllByPage(?int $page, ?int $perPage): Deployments;
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
     * @param Deployment $deployment
     * @return void
     */
    public function save(Deployment $deployment): void;
}
