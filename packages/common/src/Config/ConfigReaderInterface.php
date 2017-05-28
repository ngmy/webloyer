<?php

namespace Ngmy\Webloyer\Common\Config;

interface ConfigReaderInterface
{
    /**
     * Get configuration.
     *
     * @param string $name Configuration name
     * @return mixed
     */
    public function getConfig($name);
}

