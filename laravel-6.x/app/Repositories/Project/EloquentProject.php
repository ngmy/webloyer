<?php

namespace App\Repositories\Project;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;

class EloquentProject extends AbstractEloquentRepository implements ProjectInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $project
     * @return void
     */
    public function __construct(Model $project)
    {
        $this->model = $project;
    }

    /**
     * Get paginated projects.
     *
     * @param int $page  Page number
     * @param int $limit Number of projects per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byPage($page = 1, $limit = 10)
    {
        $projects = $this->model->with(['deployments' => function ($query) {
            $query->orderBy('number', 'desc');
        }])->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $projects;
    }
}
