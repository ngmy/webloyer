<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Webloyer\Domain\Model\Recipe\{
    Recipe,
    RecipeId,
};

class GetRecipeService extends RecipeService
{
    /**
     * @param GetRecipeRequest $request
     * @return Recipe
     */
    public function execute($request = null)
    {
        $id = new RecipeId($request->getId());
        return $this->getNonNullRecipe($id);
    }
}
