<?php
declare(strict_types=1);

namespace App\Services\Config;

use App\Services\Filesystem\FilesystemInterface;

/**
 * Class DotenvWriter
 * @package App\Services\Config
 */
class DotenvWriter implements ConfigWriterInterface
{
    /**
     * @var FilesystemInterface
     */
    protected FilesystemInterface $fs;

    /**
     * @var string
     */
    protected string $path;

    /**
     * DotenvWriter constructor.
     * @param FilesystemInterface $fs
     * @param $path
     */
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
