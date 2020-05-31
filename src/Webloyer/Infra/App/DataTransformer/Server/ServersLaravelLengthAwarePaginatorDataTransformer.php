<?php

declare(strict_types=1);

namespace Webloyer\Infra\App\DataTransformer\Server;

use Illuminate\Pagination\LengthAwarePaginator;
use Webloyer\App\DataTransformer\Server\{
    ServerDataTransformer,
    ServersDataTransformer,
    ServersDtoDataTransformer,
};
use Webloyer\Domain\Model\Server\Servers;

class ServersLaravelLengthAwarePaginatorDataTransformer implements ServersDataTransformer
{
    /** @var Servers */
    private $servers;
    /** @var ServersDtoDataTransformer */
    private $serversDataTransformer;
    /** @var int */
    private $perPage;
    /** @var int */
    private $currentPage;
    /** @var array */
    private $options;

    public function __construct(ServersDtoDataTransformer $serversDataTransformer)
    {
        $this->serversDataTransformer = $serversDataTransformer;
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();
        $this->options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ];
    }

    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @param Servers $servers
     * @return self
     */
    public function write(Servers $servers): self
    {
        $this->servers = $servers;
        return $this;
    }

    /**
     * @return Paginator
     */
    public function read()
    {
        $servers = $this->serversDataTransformer->write($this->servers)->read();
        return new LengthAwarePaginator(
            array_slice(
                $servers,
                $this->perPage * ($this->currentPage - 1),
                $this->perPage
            ),
            count($servers),
            $this->perPage,
            $this->currentPage,
            $this->options
        );
    }

    /**
     * @return ServerDataTransformer
     */
    public function serverDataTransformer(): ServerDataTransformer
    {
        return $this->serversDataTransformer->serverDataTransformer();
    }
}
