<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSetting;

interface DbSettingRepositoryInterface
{
    public function dbSetting();

    public function save(DbSetting $dbSetting);
}
