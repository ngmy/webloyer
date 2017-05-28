<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Ngmy\Webloyer\Common\Config\ConfigReaderInterface;
use Ngmy\Webloyer\Common\Config\ConfigWriterInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingDriver;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingRepositoryInterface;

class DotenvDbSettingRepository implements DbSettingRepositoryInterface
{
    private $configReader;

    private $configWriter;

    /**
     * Create a new repository instance.
     *
     * @param \Ngmy\Webloyer\Common\Config\ConfigReaderInterface $configReader
     * @param \Ngmy\Webloyer\Common\Config\ConfigWriterInterface $configWriter
     * @return void
     */
    public function __construct(ConfigReaderInterface $configReader, ConfigWriterInterface $configWriter)
    {
        $this->configReader = $configReader;
        $this->configWriter = $configWriter;
    }

    public function dbSetting()
    {
        $driver   = $this->configReader->getConfig('DB_DRIVER');
        $host     = $this->configReader->getConfig('DB_HOST');
        $database = $this->configReader->getConfig('DB_DATABASE');
        $userName = $this->configReader->getConfig('DB_USERNAME');
        $password = $this->configReader->getConfig('DB_PASSWORD');

        $dbSetting = new DbSetting(
            new DbSettingDriver($driver),
            $host,
            $database,
            $userName,
            $password
        );

        return $dbSetting;
    }

    public function save(DbSetting $dbSetting)
    {
        $this->configWriter->setConfig('DB_DRIVER',   $dbSetting->driver()->value());
        $this->configWriter->setConfig('DB_HOST',     $dbSetting->host());
        $this->configWriter->setConfig('DB_DATABASE', $dbSetting->database());
        $this->configWriter->setConfig('DB_USERNAME', $dbSetting->userName());
        $this->configWriter->setConfig('DB_PASSWORD', $dbSetting->password());

        return true;
    }
}
