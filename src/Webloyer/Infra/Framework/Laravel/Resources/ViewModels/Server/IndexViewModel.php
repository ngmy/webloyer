<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    /** @var list<object> */
    private $servers;
    /** @var int */
    private $perPage = 10;
    /** @var int */
    private $currentPage;
    /** @var array<string, string> */
    private $options;

    /**
     * @param list<object> $servers
     * @return void
     */
    public function __construct(array $servers)
    {
        $this->servers = $servers;
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();
        $this->options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ];
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function servers(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            array_slice(
                $this->servers,
                $this->perPage * ($this->currentPage - 1),
                $this->perPage
            ),
            count($this->servers),
            $this->perPage,
            $this->currentPage,
            $this->options
        );
    }

    /**
     * @return array<string, string>
     */
    public function serverProjectCountOf(): array
    {
        return array_reduce($this->servers, function (array $carry, object $server): array {
            $carry[$server->id] = number_format(count($server->projects));
            return $carry;
        }, []);
    }

    /**
     * @param int $perPage
     * @return self
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }
}
