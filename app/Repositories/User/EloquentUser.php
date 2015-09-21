<?php

namespace App\Repositories\User;

use Illuminate\Database\Eloquent\Model;

use DB;

class EloquentUser implements UserInterface
{
    protected $user;

    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     * @return void
     */
    public function __construct(Model $user)
    {
        $this->user = $user;
    }

    /**
     * Get a user by id.
     *
     * @param int $id User id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function byId($id)
    {
        return $this->user->find($id);
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
        $users = $this->user->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $users;
    }

    /**
     * Get all users.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->user->all();
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
            $user = $this->user->create($data);

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
            $user = $this->user->find($data['id']);

            $user->update($data);

            $user->revokeAllRoles();

            if (isset($data['role'])) {
                $user->assignRole($data['role']);
            }
        });

        return true;
    }

    /**
     * Delete an existing user.
     *
     * @param int $id User id
     * @return boolean
     */
    public function delete($id)
    {
        $user = $this->user->find($id);

        $user->delete();

        return true;
    }
}
