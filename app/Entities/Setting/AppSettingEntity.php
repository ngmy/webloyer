<?php
declare(strict_types=1);

namespace App\Entities\Setting;

/**
 * Class AppSettingEntity
 * @package App\Entities\Setting
 */
class AppSettingEntity extends AbstractSettingEntity
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
