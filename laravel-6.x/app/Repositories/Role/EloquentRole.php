<?php

namespace App\Repositories\Role;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;

class EloquentRole extends AbstractEloquentRepository implements RoleInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $role
     * @return void
     */
    public function __construct(Model $role)
    {
        $this->model = $role;
    }

    /**
     * Get paginated roles.
     *
     * @param int $page  Page number
     * @param int $limit Number of roles per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byPage($page = 1, $limit = 10)
    {
        $roles = $this->model->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $roles;
    }
}
