<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Deployment;

interface DeploymentSpecification
{
    /**
     * @param Deployment $deployment
     * @return bool
     */
    public function isSatisfiedBy(Deployment $deployment): bool;
    /**
     * @param DeploymentRepository $deploymentRepository
     * @return Deployments
     */
    public function satisfyingElementsFrom(DeploymentRepository $deploymentRepository): Deployments;
}
