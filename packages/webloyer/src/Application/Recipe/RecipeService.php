<?php

namespace Ngmy\Webloyer\Webloyer\Application\Recipe;

use DB;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeRepositoryInterface;

class RecipeService
{
    private $recipeRepository;

    public function __construct(RecipeRepositoryInterface $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    public function getRecipeOfId($id)
    {
        return $this->recipeRepository->recipeOfId(new RecipeId($id));
    }

    public function getAllRecipes()
    {
        return $this->recipeRepository->allRecipes();
    }

    public function getRecipesOfPage($page = 1, $perPage = 10)
    {
        return $this->recipeRepository->recipesOfPage($page, $perPage);
    }

    public function saveRecipe($id, $name, $description, $body, $concurrencyVersion)
    {
        $recipe = DB::transaction(function () use ($id, $name, $description, $body, $concurrencyVersion) {
            if (!is_null($id)) {
                $existsRecipe = $this->getRecipeOfId($id);

                if (!is_null($existsRecipe)) {
                    $existsRecipe->failWhenConcurrencyViolation($concurrencyVersion);
                }
            }

            $recipe = new Recipe(
                new RecipeId($id),
                $name,
                $description,
                $body,
                [],
                null,
                null
            );
            return $this->recipeRepository->save($recipe);
        });
        return $recipe;
    }

    public function removeRecipe($id)
    {
        return $this->recipeRepository->remove($this->getRecipeOfId($id));
    }
}
