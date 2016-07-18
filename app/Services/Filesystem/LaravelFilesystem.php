<?php

namespace App\Services\Filesystem;

use App\Services\Filesystem\FilesystemInterface;

use Illuminate\Filesystem\Filesystem;

class LaravelFilesystem implements FilesystemInterface
{
    protected $fs;

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
     * @param string $path File path
     * @return string Contents
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
