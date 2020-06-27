<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Server;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    /** @var LengthAwarePaginator<object> */
    private $servers;

    /**
     * @param LengthAwarePaginator<object> $servers
     * @return void
     */
    public function __construct(LengthAwarePaginator $servers)
    {
        $this->servers = $servers;
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function servers(): LengthAwarePaginator
    {
        return $this->servers;
    }

    /**
     * @return array<string, string>
     */
    public function serverProjectCountOf(): array
    {
        return array_reduce($this->servers->toArray()['data'], function (array $carry, object $server): array {
            $carry[$server->id] = number_format(count($server->projects));
            return $carry;
        }, []);
    }
}
