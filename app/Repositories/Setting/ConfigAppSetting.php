<?php

namespace App\Repositories\Setting;

use App\Services\Config\ConfigReaderInterface;
use App\Services\Config\ConfigWriterInterface;
use App\Entities\Setting\AppSettingEntity;
use App\Repositories\AbstractConfigRepository;
use App\Repositories\Setting\AppSettingInterface;

class ConfigAppSetting extends AbstractConfigRepository implements AppSettingInterface
{
    public function all()
    {
        $url = $this->reader->getConfig('APP_URL');

        $appSetting = new AppSettingEntity();
        $appSetting->setUrl($url);

        return $appSetting;
    }

    public function update(array $data)
    {
        $this->writer->setConfig('APP_URL', $data['url']);

        return true;
    }
}
