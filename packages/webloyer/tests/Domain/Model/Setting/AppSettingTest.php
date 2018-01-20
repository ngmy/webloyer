<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSetting;
use TestCase;

class AppSettingTest extends TestCase
{
    public function test_Should_GetUrl()
    {
        $expectedResult = 'http://example.com';

        $appSetting = new AppSetting(
            'http://example.com'
        );

        $actualResult = $appSetting->url();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            new AppSetting(
                'http://example.com'
            ),
            new AppSetting(
                'http://example.com'
            ),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            new AppSetting(
                'http://example.com'
            ),
            new AppSetting(
                'http://example.co.jp'
            ),
            false
        );
    }

    public function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }
}
