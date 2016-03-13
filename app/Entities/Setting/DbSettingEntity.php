<?php

namespace App\Entities\Setting;

use App\Entities\Setting\AbstractSettingEntity;

class DbSettingEntity extends AbstractSettingEntity
{
    protected $driver;

    protected $host;

    protected $database;

    protected $username;

    protected $password;

    public function getDriver()
    {
        return $this->driver;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getDatabase()
    {
        return $this->database;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
