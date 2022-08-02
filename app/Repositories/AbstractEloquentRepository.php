<?php
declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class AbstractEloquentRepository
 * @package App\Repositories
 */
abstract class AbstractEloquentRepository implements RepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * Get a model by id.
     *
     * @param int $id Model id
     * @return Model
     */
    public function byId($id)
    {
        return $this->model->find($id);
    }

    /**
     * Get paginated models.
     *
     * @param int $page Page number
     * @param int $limit Number of models per page
     * @return LengthAwarePaginator
     */
    public function byPage($page = 1, $limit = 10)
    {
        return $this->model
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);
    }

    /**
     * Get all models.
     *
     * @return Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Create a new model.
     *
     * @param array $data Data to create a model
     * @return Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
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
