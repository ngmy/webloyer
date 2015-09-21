<?php

namespace App\Repositories\Role;

use Illuminate\Database\Eloquent\Model;

use DB;

class EloquentRole implements RoleInterface
{
    protected $role;

    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $role
     * @return void
     */
    public function __construct(Model $role)
    {
        $this->role = $role;
    }

    /**
     * Get a role by id.
     *
     * @param int $id Role id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function byId($id)
    {
        return $this->role->find($id);
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
        $roles = $this->role->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $roles;
    }

    /**
     * Get all roles.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->role->all();
    }

    /**
     * Create a new role.
     *
     * @param array $data Data to create a role
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $role = $this->role->create($data);

        return $role;
    }

    /**
     * Update an existing role.
     *
     * @param array $data Data to update a role
     * @return boolean
     */
    public function update(array $data)
    {
        $role = $this->role->find($data['id']);

        $role->update($data);

        return true;
    }

    /**
     * Delete an existing role.
     *
     * @param int $id Role id
     * @return boolean
     */
    public function delete($id)
    {
        $role = $this->role->find($id);

        $role->delete();

        return true;
    }
}
