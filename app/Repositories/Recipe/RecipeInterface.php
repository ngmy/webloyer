<?php

namespace App\Repositories\Recipe;

interface RecipeInterface
{
    /**
     * Get a recipe by id.
     *
     * @param int $id Recipe id
     * @return mixed
     */
    public function byId($id);

    /**
     * Get paginated recipes.
     *
     * @param int $page  Page number
     * @param int $limit Number of recipes per page
     * @return mixed
     */
    public function byPage($page = 1, $limit = 10);

    /**
     * Get all recipes.
     *
     * @return mixed
     */
    public function all();

    /**
     * Create a new recipe.
     *
     * @param array $data Data to create a recipe
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing recipe.
     *
     * @param array $data Data to update a recipe
     * @return mixed
     */
    public function update(array $data);

    /**
     * Delete an existing recipe.
     *
     * @param int $id Recipe id
     * @return mixed
     */
    public function delete($id);
}
