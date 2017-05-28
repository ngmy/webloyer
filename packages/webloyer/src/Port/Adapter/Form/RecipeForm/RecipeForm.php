<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm;

use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;

class RecipeForm
{
    private $validator;

    private $recipeService;

    /**
     * Create a new form service instance.
     *
     * @param \Ngmy\Webloyer\Common\Validation\ValidableInterface      $validator
     * @param \Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService $recipeService
     * @return void
     */
    public function __construct(ValidableInterface $validator, RecipeService $recipeService)
    {
        $this->validator = $validator;
        $this->recipeService = $recipeService;
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

        $recipe = $this->recipeService->saveRecipe(
            null,
            $input['name'],
            $input['description'],
            $input['body'],
            null
        );

        return true;
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

        $recipe = $this->recipeService->saveRecipe(
            $input['id'],
            $input['name'],
            $input['description'],
            $input['body'],
            $input['concurrency_version']
        );

        return true;
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
