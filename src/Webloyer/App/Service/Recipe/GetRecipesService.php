<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

class GetRecipesService extends RecipeService
{
    /**
     * @return mixed
     */
    public function execute($request = null)
    {
        $recipes = $this->recipeRepository->findAll();
        return $this->recipesDataTransformer->write($recipes)->read();
    }
}
