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

        $dbSetting = $this->createDbSetting([
            'driver' => $expectedResult,
        ]);

        $actualResult = $dbSetting->driver();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetHost()
    {
        $expectedResult = 'mysql';

        $dbSetting = $this->createDbSetting([
            'host' => $expectedResult,
        ]);

        $actualResult = $dbSetting->host();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDatabase()
    {
        $expectedResult = 'webloyer';

        $dbSetting = $this->createDbSetting([
            'database' => $expectedResult,
        ]);

        $actualResult = $dbSetting->database();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetUserName()
    {
        $expectedResult = 'root';

        $dbSetting = $this->createDbSetting([
            'userName' => $expectedResult,
        ]);

        $actualResult = $dbSetting->userName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetPassword()
    {
        $expectedResult = 'root';

        $dbSetting = $this->createDbSetting([
            'password' => $expectedResult,
        ]);

        $actualResult = $dbSetting->password();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createDbSetting(),
            $this->createDbSetting(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createDbSetting(),
            $this->createDbSetting([
                'driver' => 'sqlite',
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createDbSetting(array $params = [])
    {
        $driver = 'mysql';
        $host = '';
        $database = '';
        $userName = '';
        $password = '';

        extract($params);

        return new DbSetting(
            new DbSettingDriver($driver),
            $host,
            $database,
            $userName,
            $password
        );
    }
}
