<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Ngmy\Webloyer\Common\Config\ConfigReaderInterface;
use Ngmy\Webloyer\Common\Config\ConfigWriterInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSetting;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSettingRepositoryInterface;

class DotenvAppSettingRepository implements AppSettingRepositoryInterface
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

    public function appSetting()
    {
        $url = $this->configReader->getConfig('APP_URL');

        $appSetting = new AppSetting($url);

        return $appSetting;
    }

    public function save(AppSetting $appSetting)
    {
        $this->configWriter->setConfig('APP_URL', $appSetting->url());

        return true;
    }
}
