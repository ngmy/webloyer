<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Server;

/**
 * @codeCoverageIgnore
 */
interface ServerRepository
{
    /**
     * @return ServerId
     */
    public function nextId(): ServerId;
    /**
     * @return Servers
     */
    public function findAll(): Servers;
    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Servers
     */
    public function findAllByPage(?int $page, ?int $perPage): Servers;
    /**
     * @param ServerId $id
     * @return Server|null
     */
    public function findById(ServerId $id): ?Server;
    /**
     * @param Server $server
     * @return void
     */
    public function remove(Server $server): void;
    /**
     * @param Server $server
     * @return void
     */
    public function save(Server $server): void;
}
