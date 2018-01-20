<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Setting;

use Ngmy\Webloyer\Webloyer\Domain\Model\AbstractEntity;

class AppSetting extends AbstractEntity
{
    private $url;

    /**
     * Create a new entity instance.
     *
     * @param string $url
     * @return void
     */
    public function __construct($url)
    {
        $this->setUrl($url);
    }

    /**
     * Get a URL.
     *
     * @access public
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Indicates whether some other object is equal to this one.
     *
     * @param object $object
     * @return bool
     */
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
