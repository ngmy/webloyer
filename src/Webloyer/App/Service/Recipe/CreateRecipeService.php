<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Recipe;

use Webloyer\Domain\Model\Recipe\Recipe;

class CreateRecipeService extends RecipeService
{
    /**
     * @param CreateRecipeRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $recipe = Recipe::of(
            $this->recipeRepository->nextId()->value(),
            $request->getName(),
            $request->getDescription(),
            $request->getBody()
        );
        $this->recipeRepository->save($recipe);
    }
}
