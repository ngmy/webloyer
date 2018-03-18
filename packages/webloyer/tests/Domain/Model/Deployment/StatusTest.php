<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Status;
use TestCase;

class StatusTest extends TestCase
{
    public function test_Shoule_CreateRunningStatusFromProcessExitCode_When_ProcessExitCodeIsNull()
    {
        $expectedResult = $this->createStatus([
            'value' => 2,
        ]);

        $actualResult = Status::fromProcessExitCode(null);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Shoule_CreateSuccessStatusFromProcessExitCode_When_ProcessExitCodeIsZero()
    {
        $expectedResult = $this->createStatus([
            'value' => 0,
        ]);

        $actualResult = Status::fromProcessExitCode(0);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Shoule_CreateFailureStatusFromProcessExitCode_When_ProcessExitCodeIsNotZero()
    {
        $expectedResult = $this->createStatus([
            'value' => 1,
        ]);

        $actualResult = Status::fromProcessExitCode(1);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetText_When_StatusIsSuccess()
    {
        $expectedResult = 'Success';

        $status = $this->createStatus([
            'value' => 0,
        ]);

        $actualResult = $status->text();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetText_When_StatusIsFailure()
    {
        $expectedResult = 'Failure';

        $status = $this->createStatus([
            'value' => 1,
        ]);

        $actualResult = $status->text();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetText_When_StatusIsRunning()
    {
        $expectedResult = 'Running';

        $status = $this->createStatus([
            'value' => 2,
        ]);

        $actualResult = $status->text();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_IsSuccessReturnTrue_When_StatusIsSuccess()
    {
        $status = $this->createStatus([
            'value' => 0,
        ]);

        $actualResult = $status->isSuccess();

        $this->assertTrue($actualResult);
    }

    public function test_Should_IsSuccessReturnFalse_When_StatusIsNotSuccess()
    {
        $status = $this->createStatus([
            'value' => 1,
        ]);

        $actualResult = $status->isSuccess();

        $this->assertFalse($actualResult);
    }

    public function test_Should_IsFailureReturnTrue_When_StatusIsFailure()
    {
        $status = $this->createStatus([
            'value' => 1,
        ]);

        $actualResult = $status->isFailure();

        $this->assertTrue($actualResult);
    }

    public function test_Should_IsFailureReturnFalse_When_StatusIsNotFailure()
    {
        $status = $this->createStatus([
            'value' => 2,
        ]);

        $actualResult = $status->isFailure();

        $this->assertFalse($actualResult);
    }

    public function test_Should_IsRunningReturnTrue_When_StatusIsRunning()
    {
        $status = $this->createStatus([
            'value' => 2,
        ]);

        $actualResult = $status->isRunning();

        $this->assertTrue($actualResult);
    }

    public function test_Should_IsRunningeturnFalse_When_StatusIsNotRunning()
    {
        $status = $this->createStatus([
            'value' => 0,
        ]);

        $actualResult = $status->isRunning();

        $this->assertFalse($actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createStatus(),
            $this->createStatus(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createStatus(),
            $this->createStatus([
                'value' => 1,
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createStatus(array $params = [])
    {
        $value = 0;

        extract($params);

        return new Status($value);
    }
}
