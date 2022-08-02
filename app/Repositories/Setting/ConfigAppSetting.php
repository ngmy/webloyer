<?php
declare(strict_types=1);

namespace App\Repositories\Setting;

use App\Entities\Setting\AppSettingEntity;
use App\Repositories\AbstractConfigRepository;

/**
 * Class ConfigAppSetting
 * @package App\Repositories\Setting
 */
class ConfigAppSetting extends AbstractConfigRepository implements AppSettingInterface
{
    /**
     * @return AppSettingEntity|mixed|void
     */
    public function all()
    {
        $url = $this->reader->getConfig('APP_URL');
        $appSetting = new AppSettingEntity;
        $appSetting->setUrl($url);
        return $appSetting;
    }

    /**
     * @param array $data
     * @return bool|mixed|void
     */
    public function update(array $data)
    {
        $this->writer->setConfig('APP_URL', $data['url']);
        return true;
    }
}
