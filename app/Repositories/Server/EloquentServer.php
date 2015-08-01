<?php

namespace App\Repositories\Server;

use Illuminate\Database\Eloquent\Model;

use DB;

class EloquentServer implements ServerInterface
{
    protected $server;

    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $server
     * @return void
     */
    public function __construct(Model $server)
    {
        $this->server = $server;
    }

    /**
     * Get a server by id.
     *
     * @param int $id Server id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function byId($id)
    {
        return $this->server->find($id);
    }

    /**
     * Get paginated servers.
     *
     * @param int $page  Page number
     * @param int $limit Number of servers per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byPage($page = 1, $limit = 10)
    {
        $servers = $this->server->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $servers;
    }

    /**
     * Get all servers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->server->all();
    }

    /**
     * Create a new server.
     *
     * @param array $data Data to create a server
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $server = $this->server->create($data);

        return $server;
    }

    /**
     * Update an existing server.
     *
     * @param array $data Data to update a server
     * @return boolean
     */
    public function update(array $data)
    {
        $server = $this->server->find($data['id']);

        $server->update($data);

        return true;
    }

    /**
     * Delete an existing server.
     *
     * @param int $id Server id
     * @return boolean
     */
    public function delete($id)
    {
        $server = $this->server->find($id);

        $server->delete();

        return true;
    }
}
