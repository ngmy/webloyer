<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ServerForm;

use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\Webloyer\Application\Server\ServerService;

class ServerForm
{
    private $validator;

    private $serverService;

    /**
     * Create a new form service instance.
     *
     * @param \Ngmy\Webloyer\Common\Validation\ValidableInterface      $validator
     * @param \Ngmy\Webloyer\Webloyer\Application\Server\ServerService $server
     * @return void
     */
    public function __construct(ValidableInterface $validator, ServerService $serverService)
    {
        $this->validator = $validator;
        $this->serverService = $serverService;
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

        $this->serverService->saveServer(
            null,
            $input['name'],
            $input['description'],
            $input['body'],
            null
        );

        return true;
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

        $this->serverService->saveServer(
            $input['id'],
            $input['name'],
            $input['description'],
            $input['body'],
            $input['concurrency_version']
        );

        return true;
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
