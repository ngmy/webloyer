<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get a model by id.
     *
     * @param int $id Model id
     * @return mixed
     */
    public function byId($id);

    /**
     * Get paginated models.
     *
     * @param int $page  Page number
     * @param int $limit Number of models per page
     * @return mixed
     */
    public function byPage($page = 1, $limit = 10);

    /**
     * Get all models.
     *
     * @return mixed
     */
    public function all();

    /**
     * Create a new model.
     *
     * @param array $data Data to create a model
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing model.
     *
     * @param array $data Data to update a model
     * @return mixed
     */
    public function update(array $data);

    /**
     * Delete an existing model.
     *
     * @param int $id Model id
     * @return mixed
     */
    public function delete($id);
}
