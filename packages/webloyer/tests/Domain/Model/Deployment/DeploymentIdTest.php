<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId;
use TestCase;

class DeploymentIdTest extends TestCase
{
    public function test_Should_GetId()
    {
        $expectedResult = 1;

        $recipe = $this->createDeploymentId([
            'id' => $expectedResult,
        ]);

        $actualResult = $recipe->id();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createDeploymentId(),
            $this->createDeploymentId([
                'id' => 1,
            ]),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createDeploymentId(),
            $this->createDeploymentId([
                'id' => 2,
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createDeploymentId(array $params = [])
    {
        $id = 1;

        extract($params);

        return new DeploymentId($id);
    }
}
