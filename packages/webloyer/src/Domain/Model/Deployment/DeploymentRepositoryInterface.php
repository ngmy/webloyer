<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Ngmy\Webloyer\Common\QueryObject\QueryObject;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\AbstractDeploymentSpecification;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;

interface DeploymentRepositoryInterface
{
    public function nextIdentity(Project $project);

    public function deployments(QueryObject $queryObject);

    public function satisfyingDeployments(AbstractDeploymentSpecification $spec);

    public function deploymentOfId(Project $project, DeploymentId $deploymentId);

    public function remove(Deployment $deployment);

    public function removeAll(array $deployments);

    public function save(Deployment $deployment);
}
