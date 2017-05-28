<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentRepositoryInterface;

abstract class AbstractDeploymentSpecification
{
    /**
     * Get elements that satisfy the specification.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentRepositoryInterface $deploymentRepository
     * @return array
     */
    abstract public function satisfyingElementsFrom(DeploymentRepositoryInterface $deploymentRepository);
}
