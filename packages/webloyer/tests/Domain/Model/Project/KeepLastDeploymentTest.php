<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Project;

use Ngmy\Webloyer\Webloyer\Domain\Model\Project\KeepLastDeployment;
use TestCase;

class KeepLastDeploymentTest extends TestCase
{
    public function test_Should_DisplayNameReturnOn_When_KeepLastDeploymentIsOn()
    {
        $expectedResult = 'On';

        $keepLastDeployment = $this->createKeepLastDeployment();

        $actualResult = $keepLastDeployment->displayName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_DisplayNameReturnOff_When_KeepLastDeploymentIsOff()
    {
        $expectedResult = 'Off';

        $keepLastDeployment = $this->createKeepLastDeployment([
            'value' => 0,
        ]);

        $actualResult = $keepLastDeployment->displayName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_IsOnReturnTrue_When_KeepLastDeploymentIsOn()
    {
        $keepLastDeployment = $this->createKeepLastDeployment();

        $actualResult = $keepLastDeployment->isOn();

        $this->assertTrue($actualResult);
    }

    public function test_Should_IsOnReturnFalse_When_KeepLastDeploymentIsOff()
    {
        $keepLastDeployment = $this->createKeepLastDeployment([
            'value' => 0,
        ]);

        $actualResult = $keepLastDeployment->isOn();

        $this->assertFalse($actualResult);
    }

    public function test_Should_IsOffReturnTrue_When_KeepLastDeploymentIsOff()
    {
        $keepLastDeployment = $this->createKeepLastDeployment([
            'value' => 0,
        ]);

        $actualResult = $keepLastDeployment->isOff();

        $this->assertTrue($actualResult);
    }

    public function test_Should_IsOffReturnFalse_When_KeepLastDeploymentIsOn()
    {
        $keepLastDeployment = $this->createKeepLastDeployment();

        $actualResult = $keepLastDeployment->isOff();

        $this->assertFalse($actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createKeepLastDeployment(),
            $this->createKeepLastDeployment(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createKeepLastDeployment(),
            $this->createKeepLastDeployment([
                'value' => 0,
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createKeepLastDeployment(array $params = [])
    {
        $value = 1;

        extract($params);

        return new KeepLastDeployment($value);
    }
}
