<?php

namespace App\Repositories\Recipe;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;

class EloquentRecipe extends AbstractEloquentRepository implements RecipeInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $recipe
     * @return void
     */
    public function __construct(Model $recipe)
    {
        $this->model = $recipe;
    }

    /**
     * Get paginated recipes.
     *
     * @param int $page  Page number
     * @param int $limit Number of recipes per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byPage($page = 1, $limit = 10)
    {
        $recipes = $this->model->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $recipes;
    }
}
