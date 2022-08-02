<?php
declare(strict_types=1);

namespace App\Services\Config;

/**
 * Interface ConfigReaderInterface
 * @package App\Services\Config
 */
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

