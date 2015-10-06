<?php

namespace App\Repositories\User;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;
use DB;

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

    /**
     * Create a new user.
     *
     * @param array $data Data to create a user
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $user = DB::transaction(function () use ($data)
        {
            $user = $this->model->create($data);

            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }

            return $user;
        });

        return $user;
    }

    /**
     * Update an existing user.
     *
     * @param array $data Data to update a user
     * @return boolean
     */
    public function update(array $data)
    {
        $user = DB::transaction(function () use ($data)
        {
            $user = $this->model->find($data['id']);

            $user->update($data);

            $user->revokeAllRoles();

            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }
        });

        return true;
    }
}
