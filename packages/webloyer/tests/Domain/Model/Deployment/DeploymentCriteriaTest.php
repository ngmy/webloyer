<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentCriteria;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use TestCase;

class DeploymentCriteriaTest extends TestCase
{
    public function test_Should_GetProjectId()
    {
        $expectedResult = new ProjectId(1);

        $deploymentCriteria = $this->createDeploymentCriteria([
            'projectId' => $expectedResult->id(),
        ]);

        $actualResult = $deploymentCriteria->projectId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createDeploymentCriteria(array $params = [])
    {
        $projectId = 1;

        extract($params);

        return new DeploymentCriteria(
            new ProjectId($projectId)
        );
    }
}
