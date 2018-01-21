<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSetting;
use TestCase;

class AppSettingTest extends TestCase
{
    public function test_Should_GetUrl()
    {
        $expectedResult = 'http://example.com';

        $appSetting = $this->createAppSetting([
            'url' => $expectedResult,
        ]);

        $actualResult = $appSetting->url();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createAppSetting(),
            $this->createAppSetting(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createAppSetting(),
            $this->createAppSetting([
                'url' => 'http://example.co.jp',
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createAppSetting(array $params = [])
    {
        $url = '';

        extract($params);

        return new AppSetting(
           $url
        );
    }
}
