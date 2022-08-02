<?php
declare(strict_types=1);

namespace App\Services\Config;

/**
 * Interface ConfigWriterInterface
 * @package App\Services\Config
 */
interface ConfigWriterInterface
{
    /**
     * Set configuration.
     *
     * @param string $name  Configuration name
     * @param string $value Configuration value
     * @return mixed
     */
    public function setConfig($name, $value);
}
