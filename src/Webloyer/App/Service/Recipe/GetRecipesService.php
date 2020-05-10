<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Webloyer\Domain\Model\Recipe\Recipes;

class GetRecipesService extends RecipeService
{
    /**
     * @param GetRecipesRequest $request
     * @return Recipes
     */
    public function execute($request = null)
    {
        return $this->recipeRepository->findAllByPage($request->getPage(), $request->getPerPage());
    }
}
