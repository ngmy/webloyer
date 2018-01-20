<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractEntity;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingDriver;

class DbSetting extends AbstractEntity
{
    private $driver;

    private $host;

    private $database;

    private $userName;

    private $password;

    /**
     * Create a new entity instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingDriver $driver
     * @param string                                                       $host
     * @param string                                                       $database
     * @param string                                                       $userName
     * @param string                                                       $password
     * @return void
     */
    public function __construct(DbSettingDriver $driver, $host, $database, $userName, $password)
    {
        $this->setDriver($driver);
        $this->setHost($host);
        $this->setDatabase($database);
        $this->setUserName($userName);
        $this->setPassword($password);
    }

    /**
     * Get a driver.
     *
     * @return Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingDriver
     */
    public function driver()
    {
        return $this->driver;
    }

    /**
     * Get a host.
     *
     * @return string
     */
    public function host()
    {
        return $this->host;
    }

    /**
     * Get a database.
     *
     * @return string
     */
    public function database()
    {
        return $this->database;
    }

    /**
     * Get a user name.
     *
     * @return string
     */
    public function userName()
    {
        return $this->userName;
    }

    /**
     * Get a password.
     *
     * @return string
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * Indicates whether some other object is equal to this one.
     *
     * @param object $object
     * @return bool
     */
    public function equals($object)
    {
        return $object == $this;
    }

    private function setDriver(DbSettingDriver $driver)
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
