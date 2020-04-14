<?php

namespace App\Services\Form\Recipe;

use App\Repositories\Recipe\RecipeInterface;

class RecipeForm
{
    protected $recipe;

    /**
     * Create a new form service instance.
     *
     * @param \App\Repositories\Recipe\RecipeInterface $recipe
     * @return void
     */
    public function __construct(RecipeInterface $recipe)
    {
        $this->recipe = $recipe;
    }

    /**
     * Create a new recipe.
     *
     * @param array $input Data to create a recipe
     * @return boolean
     */
    public function save(array $input)
    {
        return $this->recipe->create($input);
    }

    /**
     * Update an existing recipe.
     *
     * @param array $input Data to update a recipe
     * @return boolean
     */
    public function update(array $input)
    {
        return $this->recipe->update($input);
    }
}
