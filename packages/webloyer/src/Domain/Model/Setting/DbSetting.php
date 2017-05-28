<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractEntity;

class DbSetting extends AbstractEntity
{
    private $driver;

    private $host;

    private $database;

    private $userName;

    private $password;

    public function __construct($driver, $host, $database, $userName, $password)
    {
        $this->setDriver($driver);
        $this->setHost($host);
        $this->setDatabase($database);
        $this->setUserName($userName);
        $this->setPassword($password);
    }

    public function driver()
    {
        return $this->driver;
    }

    public function host()
    {
        return $this->host;
    }

    public function database()
    {
        return $this->database;
    }

    public function userName()
    {
        return $this->userName;
    }

    public function password()
    {
        return $this->password;
    }

    public function equals($object)
    {
        return $object == $this;
    }

    private function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    private function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    private function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    private function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    private function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
