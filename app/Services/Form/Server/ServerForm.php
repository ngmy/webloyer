<?php

namespace App\Services\Form\Server;

use App\Repositories\Server\ServerInterface;

class ServerForm
{
    protected $server;

    /**
     * Create a new form service instance.
     *
     * @param \App\Repositories\Server\ServerInterface $server
     * @return void
     */
    public function __construct(ServerInterface $server)
    {
        $this->server = $server;
    }

    /**
     * Create a new server.
     *
     * @param array $input Data to create a server
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->server->create($input);
    }

    /**
     * Update an existing server.
     *
     * @param array $input Data to update a server
     * @return boolean
     */
    public function update(array $input)
    {
        return $this->server->update($input);
    }
}
