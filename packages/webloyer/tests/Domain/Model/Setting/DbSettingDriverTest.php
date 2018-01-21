<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingDriver;
use TestCase;

class DbSettingDriverTest extends TestCase
{
    public function test_Should_GetDisplayName_When_Mysql()
    {
        $this->checkDisplayName('mysql', 'MySQL');
    }

    public function test_Should_GetDisplayName_When_Postgres()
    {
        $this->checkDisplayName('postgres', 'Postgres');
    }

    public function test_Should_GetDisplayName_When_Sqlite()
    {
        $this->checkDisplayName('sqlite', 'SQLite');
    }

    public function test_Should_GetDisplayName_When_SqlServer()
    {
        $this->checkDisplayName('sqlServer', 'SQL Server');
    }

    public function test_Should_IsMysqlReturnTrue_When_ThisOneIsMysql()
    {
        $dbSettingDriver = DbSettingDriver::mysql();

        $actualResult = $dbSettingDriver->isMysql();

        $this->assertTrue($actualResult);
    }

    public function test_Should_IsMysqlReturnFalse_When_ThisOneIsNotMysql()
    {
        $dbSettingDriver = DbSettingDriver::postgres();

        $actualResult = $dbSettingDriver->isMysql();

        $this->assertFalse($actualResult);
    }

    public function test_Should_IsPostgresReturnTrue_When_ThisOneIsPostgres()
    {
        $dbSettingDriver = DbSettingDriver::postgres();

        $actualResult = $dbSettingDriver->isPostgres();

        $this->assertTrue($actualResult);
    }

    public function test_Should_IsPostgresReturnFalse_When_ThisOneIsPostgres()
    {
        $dbSettingDriver = DbSettingDriver::mysql();

        $actualResult = $dbSettingDriver->isPostgres();

        $this->assertFalse($actualResult);
    }

    public function test_Should_IsSqliteReturnTrue_When_ThisOneIsSqlite()
    {
        $dbSettingDriver = DbSettingDriver::sqlite();

        $actualResult = $dbSettingDriver->isSqlite();

        $this->assertTrue($actualResult);
    }

    public function test_Should_IsSqliteReturnTrue_When_ThisOneIsNotSqlite()
    {
        $dbSettingDriver = DbSettingDriver::mysql();

        $actualResult = $dbSettingDriver->isSqlite();

        $this->assertFalse($actualResult);
    }

    public function test_Should_IsSqlServerReturnTrue_When_ThisOneIsSqlServer()
    {
        $dbSettingDriver = DbSettingDriver::sqlServer();

        $actualResult = $dbSettingDriver->isSqlServer();

        $this->assertTrue($actualResult);
    }

    public function test_Should_IsSqlServerReturnFalse_When_ThisOneIsNotSqlServer()
    {
        $dbSettingDriver = DbSettingDriver::mysql();

        $actualResult = $dbSettingDriver->isSqlServer();

        $this->assertFalse($actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createDbSettingDriver(),
            $this->createDbSettingDriver(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createDbSettingDriver(),
            $this->createDbSettingDriver([
                'value' => 'sqlite',
            ]),
            false
        );
    }

    private function checkDisplayName($value, $expectedResult)
    {
        $dbSettingDriver = DbSettingDriver::$value();

        $actualResult = $dbSettingDriver->displayName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createDbSettingDriver(array $params = [])
    {
        $value = 'mysql';

        extract($params);

        return new DbSettingDriver(
            $value
        );
    }
}
