<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Webloyer\Domain\Model\Recipe\{
    RecipeDoesNotExistException,
    RecipeId,
};

class DeleteRecipeService extends RecipeService
{
    /**
     * @param DeleteRecipeRequest $request
     * @return void
     * @throws RecipeDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $id = new RecipeId($request->getId());
        $recipe = $this->getNonNullRecipe($id);
        $this->recipeRepository->remove($recipe);
    }
}
