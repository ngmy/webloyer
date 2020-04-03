<?php

namespace App\Repositories\User;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;

class EloquentUser extends AbstractEloquentRepository implements UserInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     * @return void
     */
    public function __construct(Model $user)
    {
        $this->model = $user;
    }

    /**
     * Get paginated users.
     *
     * @param int $page  Page number
     * @param int $limit Number of users per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byPage($page = 1, $limit = 10)
    {
        $users = $this->model->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $users;
    }
}
