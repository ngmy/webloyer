<?php

namespace Ngmy\Webloyer\Common\Port\Adapter\Persistence;

use Ngmy\Webloyer\Common\Config\ConfigReaderInterface;
use Ngmy\Webloyer\Common\Filesystem\FilesystemInterface;

class DotenvConfigReader implements ConfigReaderInterface
{
    private $fs;

    private $path;

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

    private function nullIfBlank($value)
    {
        return trim($value) !== '' ? $value : null;
    }
}
