<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingDriver;
use TestCase;

class MailSettingDriverTest extends TestCase
{
    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createMailSettingDriver(),
            $this->createMailSettingDriver(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createMailSettingDriver(),
            $this->createMailSettingDriver([
                'value' => 'mail',
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createMailSettingDriver(array $params = [])
    {
        $value = 'smtp';

        extract($params);

        return new MailSettingDriver(
            $value
        );
    }
}
