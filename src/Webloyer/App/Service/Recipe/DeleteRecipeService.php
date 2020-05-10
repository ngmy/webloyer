<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Webloyer\Domain\Model\Recipe\RecipeId;

class DeleteRecipeService extends RecipeService
{
    /**
     * @param DeleteRecipeRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $id = new RecipeId($request->getId());
        $recipe = $this->getNonNullRecipe($id);
        $this->recipeRepository->remove($recipe);
    }
}
