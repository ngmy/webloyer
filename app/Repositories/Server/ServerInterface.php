<?php

namespace App\Repositories\Server;

interface ServerInterface
{
    /**
     * Get a server by id.
     *
     * @param int $id Server id
     * @return mixed
     */
    public function byId($id);

    /**
     * Get paginated servers.
     *
     * @param int $page  Page number
     * @param int $limit Number of servers per page
     * @return mixed
     */
    public function byPage($page = 1, $limit = 10);

    /**
     * Get all servers.
     *
     * @return mixed
     */
    public function all();

    /**
     * Create a new server.
     *
     * @param array $data Data to create a server
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing server.
     *
     * @param array $data Data to update a server
     * @return mixed
     */
    public function update(array $data);

    /**
     * Delete an existing server.
     *
     * @param int $id Server id
     * @return mixed
     */
    public function delete($id);
}
