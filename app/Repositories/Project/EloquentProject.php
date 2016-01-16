<?php

namespace App\Repositories\Project;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;
use DB;

class EloquentProject extends AbstractEloquentRepository implements ProjectInterface
{
    protected $maxDeployment;

    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $project
     * @param \Illuminate\Database\Eloquent\Model $maxDeployment
     * @return void
     */
    public function __construct(Model $project, Model $maxDeployment)
    {
        $this->model = $project;
        $this->maxDeployment = $maxDeployment;
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
        $projects = $this->model->with(['deployments' => function ($query)
        {
            $query->orderBy('number', 'desc');
        }])->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $projects;
    }

    /**
     * Create a new project.
     *
     * @param array $data Data to create a project
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $project = DB::transaction(function () use ($data)
        {
            // Insert data to `project` table
            $project = $this->model->create($data);

            // Insert data to `max_deployment` table
            $this->maxDeployment->project_id = $project->id;
            $this->maxDeployment->save();

            // Replace data in `project_recipe` table
            $this->syncRecipes($project, $data['recipe_id']);

            return $project;
        });

        return $project;
    }

    /**
     * Update an existing project.
     *
     * @param array $data Data to update a project
     * @return boolean
     */
    public function update(array $data)
    {
        $project = DB::transaction(function () use ($data)
        {
            // Update data in `project` table
            $project = $this->model->find($data['id']);

            $project->update($data);

            // Replace data in `project_recipe` table
            $this->syncRecipes($project, $data['recipe_id']);
        });

        return true;
    }

    /**
     * Sync recipes for a project.
     *
     * @param \Illuminate\Database\Eloquent\Model $project
     * @param array                               $recipes
     * @return void
     */
    protected function syncRecipes(Model $project, array $recipes)
    {
        foreach ($recipes as $i => $recipeId) {
            $syncRecipeIds[$recipeId] = ['recipe_order' => $i + 1];
        }

        $project->recipes()->sync($syncRecipeIds);
    }
}
