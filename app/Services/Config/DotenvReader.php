<?php

namespace App\Services\Config;

use App\Services\Config\ConfigReaderInterface;
use App\Services\Filesystem\FilesystemInterface;

class DotenvReader implements ConfigReaderInterface
{
    protected $fs;

    protected $path;

    public function __construct(FilesystemInterface $fs, $path)
    {
        $this->fs   = $fs;
        $this->path = $path;
    }

    /**
     * Get configuration from a .env file.
     *
     * @param string $name Configuration name
     * @return string|null
     */
    public function getConfig($name)
    {
        $contents = $this->fs->get($this->path);

        if (preg_match("/^$name=(.*)$/m", $contents, $matches)) {
            $value = $matches[1];
            $value = $this->nullIfBlank($value);
        } else {
            $value = null;
        }

        return $value;
    }

    protected function nullIfBlank($value)
    {
        return trim($value) !== '' ? $value : null;
    }
}
