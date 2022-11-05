<?php
declare(strict_types=1);

namespace App\Repositories\Role;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class EloquentRole
 * @package App\Repositories\Role
 */
class EloquentRole extends AbstractEloquentRepository implements RoleInterface
{
    /**
     * EloquentRole constructor.
     * @param Model $role
     */
    public function __construct(Model $role)
    {
        $this->model = $role;
    }

    /**
     * Get paginated roles.
     *
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
