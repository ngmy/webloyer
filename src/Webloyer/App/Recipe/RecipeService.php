<?php

declare(strict_types=1);

namespace Webloyer\App\Recipe;

use Webloyer\Domain\Model\Recipe\{
    Recipe,
    RecipeRepository,
};

class RecipeService
{
    /** @var RecipeRepository */
    private $recipeRepository;

    /**
     * @param RecipeRepository $recipeRepository
     * @return void
     */
    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * @param StoreRecipeCommand $command
     * @return void
     */
    public function storeRecipe(StoreRecipeCommand $command): void
    {
        $recipe = Recipe::of(
            $command->getName(),
            $command->getDescription(),
            $command->getBody(),
        );
        $this->recipeRepository->save($recipe);
    }
}
