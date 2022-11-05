<?php
declare(strict_types=1);

namespace App\Repositories\Recipe;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class EloquentRecipe
 * @package App\Repositories\Recipe
 */
class EloquentRecipe extends AbstractEloquentRepository implements RecipeInterface
{
    /**
     * Create a new repository instance.
     *
     * @param Model $recipe
     * @return void
     */
    public function __construct(Model $recipe)
    {
        $this->model = $recipe;
    }

    /**
     * Get paginated recipes.
     *
     * @param int $page Page number
     * @param int $limit Number of recipes per page
     * @return LengthAwarePaginator
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
