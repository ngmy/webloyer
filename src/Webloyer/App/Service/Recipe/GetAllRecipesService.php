<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

class GetAllRecipesService extends RecipeService
{
    /**
     * @return mixed
     */
    public function execute($request = null)
    {
        return $this->recipeRepository->findAll();
    }
}
