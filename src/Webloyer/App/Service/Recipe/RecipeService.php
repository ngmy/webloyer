<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Common\App\Service\ApplicationService;
use Webloyer\App\DataTransformer\Recipe\{
    RecipeDataTransformer,
    RecipesDataTransformer,
};
use Webloyer\Domain\Model\Recipe\{
    Recipe,
    RecipeDoesNotExistException,
    RecipeId,
    RecipeRepository,
};

abstract class RecipeService implements ApplicationService
{
    /** @var RecipeRepository */
    protected $recipeRepository;
    /** @var RecipeDataTransformer */
    protected $recipeDataTransformer;
    /** @var RecipesDataTransformer */
    protected $recipesDataTransformer;

    /**
     * @param RecipeRepository       $recipeRepository
     * @param RecipeDataTransformer  $recipeDataTransformer
     * @param RecipesDataTransformer $recipesDataTransformer
     * @return void
     */
    public function __construct(
        RecipeRepository $recipeRepository,
        RecipeDataTransformer $recipeDataTransformer,
        RecipesDataTransformer $recipesDataTransformer
    ) {
        $this->recipeRepository = $recipeRepository;
        $this->recipeDataTransformer = $recipeDataTransformer;
        $this->recipesDataTransformer = $recipesDataTransformer;
    }

    /**
     * @return RecipeDataTransformer
     */
    public function recipeDataTransformer(): RecipeDataTransformer
    {
        return $this->recipeDataTransformer;
    }

    /**
     * @return RecipesDataTransformer
     */
    public function recipesDataTransformer(): RecipesDataTransformer
    {
        return $this->recipesDataTransformer;
    }

    /**
     * @param RecipeId $id
     * @return Recipe
     * @throws RecipeDoesNotExistException
     */
    protected function getNonNullRecipe(RecipeId $id): Recipe
    {
        $recipe = $this->recipeRepository->findById($id);
        if (is_null($recipe)) {
            throw new RecipeDoesNotExistException(
                'Recipe does not exist.' . PHP_EOL .
                'Id: ' . $id->value()
            );
        }
        return $recipe;
    }
}
