<?php

namespace App\Repositories\Recipe;

use Illuminate\Database\Eloquent\Model;

use DB;

class EloquentRecipe implements RecipeInterface
{
    protected $recipe;

    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $recipe
     * @return void
     */
    public function __construct(Model $recipe)
    {
        $this->recipe = $recipe;
    }

    /**
     * Get a recipe by id.
     *
     * @param int $id Recipe id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function byId($id)
    {
        return $this->recipe->find($id);
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
        $recipes = $this->recipe->orderBy('name')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $recipes;
    }

    /**
     * Get all recipes.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->recipe->all();
    }

    /**
     * Create a new recipe.
     *
     * @param array $data Data to create a recipe
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $recipe = $this->recipe->create($data);

        return $recipe;
    }

    /**
     * Update an existing recipe.
     *
     * @param array $data Data to update a recipe
     * @return boolean
     */
    public function update(array $data)
    {
        $recipe = $this->recipe->find($data['id']);

        $recipe->update($data);

        return true;
    }

    /**
     * Delete an existing recipe.
     *
     * @param int $id Recipe id
     * @return boolean
     */
    public function delete($id)
    {
        $recipe = $this->recipe->find($id);

        $recipe->delete();

        return true;
    }
}
