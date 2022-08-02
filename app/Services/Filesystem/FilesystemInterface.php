<?php
declare(strict_types=1);

namespace App\Services\Filesystem;

interface FilesystemInterface
{
    /**
     * Write a file.
     *
     * @param string $path     File path
     * @param string $contents Contents to write a file
     * @return mixed
     */
    public function put($path, $contents);

    /**
     * Read a file.
     *
     * @param string $path File path
     * @return string Contents
     */
    public function get($path);
}
