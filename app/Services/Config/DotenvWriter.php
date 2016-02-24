<?php

namespace App\Services\Config;

use App\Services\Config\ConfigWriterInterface;
use App\Services\Filesystem\FilesystemInterface;

class DotenvWriter implements ConfigWriterInterface
{
    protected $fs;

    protected $path;

    public function __construct(FilesystemInterface $fs, $path)
    {
        $this->fs   = $fs;
        $this->path = $path;
    }

    /**
     * Set configuration to a .env file.
     *
     * @param string $name  Configuration name
     * @param string $value Configuration value
     * @return mixed
     */
    public function setConfig($name, $value)
    {
        $contents = $this->fs->get($this->path);

        if (preg_match("/^$name=.*$/m", $contents)) {
            $contents = preg_replace("/^$name=.*$/m", "$name=$value", $contents);
        } else {
            $contents .= "$name=$value".PHP_EOL;
        }

        return $this->fs->put($this->path, $contents);
    }
}
