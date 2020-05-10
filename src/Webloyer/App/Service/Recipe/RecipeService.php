<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Common\App\Service\ApplicationService;
use InvalidArgumentException;
use Webloyer\Domain\Model\Recipe\{
    Recipe,
    RecipeId,
    RecipeRepository,
};

abstract class RecipeService implements ApplicationService
{
    /** @var RecipeRepository */
    protected $recipeRepository;

    /**
     * @param RecipeRepository $recipeRepository
     * @return void
     */
    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * @param RecipeId $id
     * @return Recipe
     * @throws InvalidArgumentException
     */
    protected function getNonNullRecipe(RecipeId $id): Recipe
    {
        $recipe = $this->recipeRepository->findById($id);
        if (is_null($recipe)) {
            throw new InvalidArgumentException(
                'Recipe does not exists.' . PHP_EOL .
                'Id: ' . $id->value()
            );
        }
        return $recipe;
    }
}
