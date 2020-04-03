<?php

namespace App\Repositories;

use App\Services\Config\ConfigReaderInterface;
use App\Services\Config\ConfigWriterInterface;

abstract class AbstractConfigRepository implements RepositoryInterface
{
    protected $reader;

    protected $writer;

    /**
     * Create a new repository instance.
     *
     * @param \App\Services\Config\ConfigReaderInterface $reader
     * @param \App\Services\Config\ConfigWriterInterface $writer
     * @return void
     */
    public function __construct(ConfigReaderInterface $reader, ConfigWriterInterface $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    public function byId($id)
    {
    }

    public function byPage($page = 1, $limi = 10)
    {
    }

    public function all()
    {
    }

    public function create(array $data)
    {
    }

    public function update(array $data)
    {
    }

    public function delete($id)
    {
    }
}
