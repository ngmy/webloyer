<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingDriver;
use TestCase;

class DbSettingTest extends TestCase
{
    public function test_Should_GetDriver()
    {
        $expectedResult = 'mysql';
        $dbSetting = new DbSetting(
            new DbSettingDriver($expectedResult),
            'host',
            'database',
            'userName',
            'password'
        );

        $actualResult = $dbSetting->driver();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetHost()
    {
        $expectedResult = 'host';
        $dbSetting = new DbSetting(
            new DbSettingDriver('mysql'),
            $expectedResult,
            'database',
            'userName',
            'password'
        );

        $actualResult = $dbSetting->host();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDatabase()
    {
        $expectedResult = 'database';
        $dbSetting = new DbSetting(
            new DbSettingDriver('mysql'),
            'host',
            $expectedResult,
            'userName',
            'password'
        );

        $actualResult = $dbSetting->database();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetUserName()
    {
        $expectedResult = 'userName';
        $dbSetting = new DbSetting(
            new DbSettingDriver('mysql'),
            'host',
            'database',
            $expectedResult,
            'password'
        );

        $actualResult = $dbSetting->userName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetPassword()
    {
        $expectedResult = 'password';
        $dbSetting = new DbSetting(
            new DbSettingDriver('mysql'),
            'host',
            'database',
            'userName',
            $expectedResult
        );

        $actualResult = $dbSetting->password();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            new DbSetting(
                new DbSettingDriver('mysql'),
                'host',
                'database',
                'userName',
                'password'
            ),
            new DbSetting(
                new DbSettingDriver('mysql'),
                'host',
                'database',
                'userName',
                'password'
            ),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            new DbSetting(
                new DbSettingDriver('mysql'),
                'host',
                'database',
                'userName',
                'password'
            ),
            new DbSetting(
                new DbSettingDriver('sqlite'),
                'host',
                'database',
                'userName',
                'password'
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
