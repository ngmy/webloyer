<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Webloyer\Domain\Model\Recipe\Recipes;

class GetRecipesService extends RecipeService
{
    /**
     * @param GetRecipesRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $recipes = $this->recipeRepository->findAll();
        return $this->recipesDataTransformer->write($recipes)->read();
    }
}
