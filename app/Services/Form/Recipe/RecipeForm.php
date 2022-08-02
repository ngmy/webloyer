<?php
declare(strict_types=1);

namespace App\Services\Form\Recipe;

use App\Services\Validation\ValidableInterface;
use App\Repositories\Recipe\RecipeInterface;

/**
 * Class RecipeForm
 * @package App\Services\Form\Recipe
 */
class RecipeForm
{
    /**
     * @var ValidableInterface
     */
    protected ValidableInterface $validator;

    /**
     * @var RecipeInterface
     */
    protected RecipeInterface $recipe;

    /**
     * Create a new form service instance.
     *
     * @param ValidableInterface $validator
     * @param RecipeInterface $recipe
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
