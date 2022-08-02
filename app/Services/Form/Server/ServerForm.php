<?php
declare(strict_types=1);

namespace App\Services\Form\Server;

use App\Services\Validation\ValidableInterface;
use App\Repositories\Server\ServerInterface;

/**
 * Class ServerForm
 * @package App\Services\Form\Server
 */
class ServerForm
{
    /**
     * @var ValidableInterface
     */
    protected ValidableInterface $validator;

    /**
     * @var ServerInterface
     */
    protected ServerInterface $server;

    /**
     * Create a new form service instance.
     *
     * @param ValidableInterface $validator
     * @param ServerInterface $server
     * @return void
     */
    public function __construct(ValidableInterface $validator, ServerInterface $server)
    {
        $this->validator = $validator;
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
        if (!$this->valid($input)) {
            return false;
        }
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
        if (!$this->valid($input)) {
            return false;
        }
        return $this->server->update($input);
    }

    /**
     * Return validation errors.
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * Test whether form validator passes.
     *
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }
}
