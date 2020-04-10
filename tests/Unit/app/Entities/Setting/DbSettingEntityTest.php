<?php

namespace Tests\Unit\app\Entities\Setting;

use App\Entities\Setting\DbSettingEntity;
use Tests\TestCase;

class DbSettingEntityTest extends TestCase
{
    public function test_Should_SetAndGetDriver()
    {
        $dbSettingEntity = new DbSettingEntity();

        $dbSettingEntity->setDriver('mysql');

        $driver = $dbSettingEntity->getDriver();

        $this->assertEquals('mysql', $driver);
    }

    public function test_Should_SetAndGetHost()
    {
        $dbSettingEntity = new DbSettingEntity();

        $dbSettingEntity->setHost('localhost');

        $retHost = $dbSettingEntity->getHost();

        $this->assertEquals('localhost', $retHost);
    }

    public function test_Should_SetAndGetDatabase()
    {
        $dbSettingEntity = new DbSettingEntity();

        $dbSettingEntity->setDatabase('webloyer');

        $database = $dbSettingEntity->getDatabase();

        $this->assertEquals('webloyer', $database);
    }

    public function test_Should_SetAndGetUsername()
    {
        $dbSettingEntity = new DbSettingEntity();

        $dbSettingEntity->setUsername('username');

        $username = $dbSettingEntity->getUsername();

        $this->assertEquals('username', $username);
    }

    public function test_Should_SetAndGetPassword()
    {
        $dbSettingEntity = new DbSettingEntity();

        $dbSettingEntity->setPassword('password');

        $password = $dbSettingEntity->getPassword();

        $this->assertEquals('password', $password);
    }
}
