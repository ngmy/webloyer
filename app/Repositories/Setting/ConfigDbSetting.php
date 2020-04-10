<?php

namespace App\Repositories\Setting;

use App\Services\Config\ConfigReaderInterface;
use App\Services\Config\ConfigWriterInterface;
use App\Entities\Setting\DbSettingEntity;
use App\Repositories\AbstractConfigRepository;
use App\Repositories\Setting\DbSettingInterface;

class ConfigDbSetting extends AbstractConfigRepository implements DbSettingInterface
{
    public function all()
    {
        $driver   = $this->reader->getConfig('DB_DRIVER');
        $host     = $this->reader->getConfig('DB_HOST');
        $database = $this->reader->getConfig('DB_DATABASE');
        $username = $this->reader->getConfig('DB_USERNAME');
        $password = $this->reader->getConfig('DB_PASSWORD');

        $dbSetting = new DbSettingEntity();
        $dbSetting->setDriver($driver)
            ->setHost($host)
            ->setDatabase($database)
            ->setUsername($username)
            ->setPassword($password);

        return $dbSetting;
    }

    public function update(array $data)
    {
        $this->writer->setConfig('DB_DRIVER', $data['driver']);
        $this->writer->setConfig('DB_HOST', $data['host']);
        $this->writer->setConfig('DB_DATABASE', $data['database']);
        $this->writer->setConfig('DB_USERNAME', $data['username']);
        $this->writer->setConfig('DB_PASSWORD', $data['password']);

        return true;
    }
}
