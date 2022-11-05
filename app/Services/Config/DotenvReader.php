<?php
declare(strict_types=1);

namespace App\Services\Config;

use App\Services\Filesystem\FilesystemInterface;

/**
 * Class DotenvReader
 * @package App\Services\Config
 */
class DotenvReader implements ConfigReaderInterface
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
     * DotenvReader constructor.
     * @param FilesystemInterface $fs
     * @param $path
     */
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

    /**
     * @param $value
     * @return |null
     */
    protected function nullIfBlank($value)
    {
        return trim($value) !== '' ? $value : null;
    }
}
