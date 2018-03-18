<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Task;
use TestCase;

class TaskTest extends TestCase
{
    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createTask(),
            $this->createTask(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createTask(),
            $this->createTask([
                'value' => 'rollback',
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createTask(array $params = [])
    {
        $value = 'deploy';

        extract($params);

        return new Task($value);
    }
}
