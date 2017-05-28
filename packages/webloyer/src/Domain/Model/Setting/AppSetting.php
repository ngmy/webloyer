<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractEntity;

class AppSetting extends AbstractEntity
{
    private $url;

    public function __construct($url)
    {
        $this->setUrl($url);
    }

    public function url()
    {
        return $this->url;
    }

    public function equals($object)
    {
        return $object == $this;
    }

    private function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
