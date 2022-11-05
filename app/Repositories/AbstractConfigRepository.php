<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Services\Config\ConfigReaderInterface;
use App\Services\Config\ConfigWriterInterface;

/**
 * Class AbstractConfigRepository
 * @package App\Repositories
 */
abstract class AbstractConfigRepository implements RepositoryInterface
{
    /**
     * @var ConfigReaderInterface
     */
    protected ConfigReaderInterface $reader;

    /**
     * @var ConfigWriterInterface
     */
    protected ConfigWriterInterface $writer;

    /**
     * AbstractConfigRepository constructor.
     * @param ConfigReaderInterface $reader
     * @param ConfigWriterInterface $writer
     */
    public function __construct(ConfigReaderInterface $reader, ConfigWriterInterface $writer)
    {
        $this->reader = $reader;
        $this->writer = $writer;
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function byId($id)
    {
    }

    /**
     * @param int $page
     * @param int $limi
     * @return mixed|void
     */
    public function byPage($page = 1, $limi = 10)
    {
    }

    /**
     * @return mixed|void
     */
    public function all()
    {
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function create(array $data)
    {
    }

    /**
     * @param array $data
     * @return mixed|void
     */
    public function update(array $data)
    {
    }

    /**
     * @param int $id
     * @return mixed|void
     */
    public function delete($id)
    {
    }
}
