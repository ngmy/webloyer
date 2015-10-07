<?php

namespace App\Repositories;

abstract class AbstractEloquentRepository implements RepositoryInterface
{
    protected $model;

    /**
     * Get a model by id.
     *
     * @param int $id Model id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function byId($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get paginated models.
     *
     * @param int $page  Page number
     * @param int $limit Number of models per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byPage($page = 1, $limit = 10)
    {
        $models = $this->model
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $models;
    }

    /**
     * Get all models.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Create a new model.
     *
     * @param array $data Data to create a model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $model = $this->model->create($data);

        return $model;
    }

    /**
     * Update an existing model.
     *
     * @param array $data Data to update a model
     * @return boolean
     */
    public function update(array $data)
    {
        $model = $this->model->find($data['id']);

        $model->update($data);

        return true;
    }

    /**
     * Delete an existing model.
     *
     * @param int $id Model id
     * @return boolean
     */
    public function delete($id)
    {
        $model = $this->model->find($id);

        $model->delete();

        return true;
    }
}
