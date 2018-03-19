<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Project;

use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectAttribute;
use TestCase;

class ProjectAttributeTest extends TestCase
{
    public function test_Should_GetDeployPath()
    {
        $expectedResult = '/some/deploy/path';

        $projectAttribute = $this->createProjectAttribute([
            'deployPath' => $expectedResult,
        ]);

        $actualResult = $projectAttribute->deployPath();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createProjectAttribute(),
            $this->createProjectAttribute(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createProjectAttribute(),
            $this->createProjectAttribute([
                'deployPath' => '/different/deploy/path',
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createProjectAttribute(array $params = [])
    {
        $deployPath = '/some/deploy/path';

        extract($params);

        return new ProjectAttribute($deployPath);
    }
}
