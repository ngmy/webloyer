<?php

namespace App\Repositories\Server;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;

class EloquentServer extends AbstractEloquentRepository implements ServerInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $server
     * @return void
     */
    public function __construct(Model $server)
    {
        $this->model = $server;
    }

    /**
     * Get paginated servers.
     *
     * @param int $page  Page number
     * @param int $limit Number of servers per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byPage($page = 1, $limit = 10)
    {
        $servers = $this->model->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $servers;
    }
}
