<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Project;

use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use TestCase;

class ProjectIdTest extends TestCase
{
    public function test_Should_GetId()
    {
        $expectedResult = 1;

        $projectId = $this->createProjectId([
            'id' => $expectedResult,
        ]);

        $actualResult = $projectId->id();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createProjectId(),
            $this->createProjectId(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createProjectId(),
            $this->createProjectId([
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

    private function createProjectId(array $params = [])
    {
        $id = 1;

        extract($params);

        return new ProjectId($id);
    }
}
