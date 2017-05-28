<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSetting;

interface AppSettingRepositoryInterface
{
    public function appSetting();

    public function save(AppSetting $appSetting);
}
