<?php

namespace App\Repositories\Role;

interface RoleInterface
{
    /**
     * Get a role by id.
     *
     * @param int $id Role id
     * @return mixed
     */
    public function byId($id);

    /**
     * Get paginated roles.
     *
     * @param int $page  Page number
     * @param int $limit Number of roles per page
     * @return mixed
     */
    public function byPage($page = 1, $limit = 10);

    /**
     * Get all roles.
     *
     * @return mixed
     */
    public function all();

    /**
     * Create a new role.
     *
     * @param array $data Data to create a role
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing role.
     *
     * @param array $data Data to update a role
     * @return mixed
     */
    public function update(array $data);

    /**
     * Delete an existing role.
     *
     * @param int $id Role id
     * @return mixed
     */
    public function delete($id);
}
