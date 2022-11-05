<?php
declare(strict_types=1);

namespace App\Services\Filesystem;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

/**
 * Class LaravelFilesystem
 * @package App\Services\Filesystem
 */
class LaravelFilesystem implements FilesystemInterface
{
    /**
     * @var Filesystem
     */
    protected Filesystem $fs;

    /**
     * LaravelFilesystem constructor.
     * @param Filesystem $fs
     */
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }

    /**
     * Write a file.
     *
     * @param string $path     File path
     * @param string $contents Contents to write a file
     * @return mixed
     */
    public function put($path, $contents)
    {
        return $this->fs->put($path, $contents);
    }

    /**
     * Read a file.
     *
     * @param string $path
     * @return string
     * @throws FileNotFoundException
     */
    public function get($path)
    {
        return $this->fs->get($path);
    }

    /**
     * Delete a file.
     *
     * @param string $path File path
     * @return boolean
     */
    public function delete($path)
    {
        return $this->fs->delete($path);
    }
}
