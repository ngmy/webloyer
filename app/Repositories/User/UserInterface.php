<?php

namespace App\Repositories\User;

interface UserInterface
{
    /**
     * Get a user by id.
     *
     * @param int $id User id
     * @return mixed
     */
    public function byId($id);

    /**
     * Get paginated users.
     *
     * @param int $page  Page number
     * @param int $limit Number of users per page
     * @return mixed
     */
    public function byPage($page = 1, $limit = 10);

    /**
     * Get all users.
     *
     * @return mixed
     */
    public function all();

    /**
     * Create a new user.
     *
     * @param array $data Data to create a user
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing user.
     *
     * @param array $data Data to update a user
     * @return mixed
     */
    public function update(array $data);

    /**
     * Delete an existing user.
     *
     * @param int $id User id
     * @return mixed
     */
    public function delete($id);
}
