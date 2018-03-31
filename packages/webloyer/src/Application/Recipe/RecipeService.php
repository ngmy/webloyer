<?php

namespace Ngmy\Webloyer\Webloyer\Application\Recipe;

use DB;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeRepositoryInterface;

class RecipeService
{
    private $recipeRepository;

    /**
     * Create a new application service instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeRepositoryInterface $recipeRepository
     * @return void
     */
    public function __construct(RecipeRepositoryInterface $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * Get all recipes.
     *
     * @return array
     */
    public function getAllRecipes()
    {
        return $this->recipeRepository->allRecipes();
    }

    /**
     * Get recipes by page.
     *
     * @param int $page
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getRecipesByPage($page = 1, $perPage = 10)
    {
        return $this->recipeRepository->recipesOfPage($page, $perPage);
    }

    /**
     * Get a recipe by id.
     *
     * @param int $recipeId
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe
     */
    public function getRecipeById($recipeId)
    {
        return $this->recipeRepository->recipeOfId(new RecipeId($recipeId));
    }

    /**
     * Create or Update a recipe.
     *
     * @param int|null $recipeId
     * @param string   $name
     * @param string   $description
     * @param string   $body
     * @param string   $concurrencyVersion
     * @return void
     */
    public function saveRecipe($recipeId, $name, $description, $body, $concurrencyVersion)
    {
        DB::transaction(function () use ($recipeId, $name, $description, $body, $concurrencyVersion) {
            if (!is_null($recipeId)) {
                $existsRecipe = $this->getRecipeById($recipeId);

                if (!is_null($existsRecipe)) {
                    $existsRecipe->failWhenConcurrencyViolation($concurrencyVersion);
                }
            }
            $recipe = new Recipe(
                new RecipeId($recipeId),
                $name,
                $description,
                $body,
                [],
                null,
                null
            );
            $this->recipeRepository->save($recipe);
        });
    }

    /**
     * Remove a recipe.
     *
     * @param int $recipeId
     * @return void
     */
    public function removeRecipe($recipeId)
    {
        $this->recipeRepository->remove($this->getRecipeById($recipeId));
    }
}
