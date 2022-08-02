<?php
declare(strict_types=1);

namespace App\Repositories\Project;

use App\Repositories\AbstractEloquentRepository;
use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class EloquentProject
 * @package App\Repositories\Project
 */
class EloquentProject extends AbstractEloquentRepository implements ProjectInterface
{
    /**
     * EloquentProject constructor.
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        $this->model = $project;
    }

    /**
     * Get paginated projects.
     *
     * @param int $page Page number
     * @param int $limit Number of projects per page
     * @return LengthAwarePaginator
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
