<?php
declare(strict_types=1);

namespace App\Entities\Setting;

/**
 * Class DbSettingEntity
 * @package App\Entities\Setting
 */
class DbSettingEntity extends AbstractSettingEntity
{
    /**
     * @var string
     */
    protected string $driver;

    /**
     * @var string
     */
    protected string $host;

    /**
     * @var string
     */
    protected string $database;

    /**
     * @var string
     */
    protected string $username;

    /**
     * @var string
     */
    protected string $password;

    /**
     * @return mixed
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $driver
     * @return $this
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @param $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param $database
     * @return $this
     */
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * @param $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}
