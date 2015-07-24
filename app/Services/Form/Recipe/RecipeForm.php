<?php

namespace App\Services\Form\Recipe;

use App\Services\Validation\ValidableInterface;
use App\Repositories\Recipe\RecipeInterface;

class RecipeForm
{
    protected $validator;

    protected $recipe;

    /**
     * Create a new form service instance.
     *
     * @param \App\Services\Validation\ValidableInterface $validator
     * @param \App\Repositories\Recipe\RecipeInterface    $recipe
     * @return void
     */
    public function __construct(ValidableInterface $validator, RecipeInterface $recipe)
    {
        $this->validator = $validator;
        $this->recipe    = $recipe;
    }

    /**
     * Create a new recipe.
     *
     * @param array $input Data to create a recipe
     * @return boolean
     */
    public function save(array $input)
    {
        if (!$this->valid($input)) {
            return false;
        }

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
        if (!$this->valid($input)) {
            return false;
        }

        return $this->recipe->update($input);
    }

    /**
     * Return validation errors.
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * Test whether form validator passes.
     *
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }
}
