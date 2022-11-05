<?php
declare(strict_types=1);

namespace App\Repositories\Server;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class EloquentServer
 * @package App\Repositories\Server
 */
class EloquentServer extends AbstractEloquentRepository implements ServerInterface
{
    /**
     * EloquentServer constructor.
     * @param Model $server
     */
    public function __construct(Model $server)
    {
        $this->model = $server;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return LengthAwarePaginator|mixed
     */
    public function byPage($page = 1, $limit = 10)
    {
        return $this->model->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);
    }
}
