<?php

namespace App\Entities\Setting;

use App\Entities\Setting\AbstractSettingEntity;

class AppSettingEntity extends AbstractSettingEntity
{
    protected $url;

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
