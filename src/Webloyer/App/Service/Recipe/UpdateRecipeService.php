<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Webloyer\Domain\Model\Recipe\{
    RecipeBody,
    RecipeDescription,
    RecipeId,
    RecipeName,
};

class UpdateRecipeService extends RecipeService
{
    /**
     * @param UpdateRecipeRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $id = new RecipeId($request->getId());
        $name = new RecipeName($request->getName());
        $description = new RecipeDescription($request->getDescription());
        $body = new RecipeBody($request->getBody());
        $recipe = $this->getNonNullRecipe($id)
            ->changeName($name)
            ->changeDescription($description)
            ->changeBody($body);
        $this->recipeRepository->save($recipe);
    }
}
