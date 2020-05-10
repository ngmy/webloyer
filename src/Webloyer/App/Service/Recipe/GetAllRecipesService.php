<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Webloyer\Domain\Model\Recipe\Recipes;

class GetAllRecipesService extends RecipeService
{
    /**
     * @return Recipes
     */
    public function execute($request = null)
    {
        return $this->recipeRepository->findAll();
    }
}
