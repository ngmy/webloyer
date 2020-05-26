<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Webloyer\Domain\Model\Recipe\RecipeId;

class GetRecipeService extends RecipeService
{
    /**
     * @param GetRecipeRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $id = new RecipeId($request->getId());
        $recipe = $this->getNonNullRecipe($id);
        return $this->recipeDataTransformer->write($recipe)->read();
    }
}
