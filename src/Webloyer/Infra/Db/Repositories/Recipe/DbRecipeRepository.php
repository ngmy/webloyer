<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Repositories\Recipe;

use Webloyer\Domain\Model\Recipe\{
    Recipe,
    RecipeRepository,
};
use Webloyer\Infra\Db\Eloquents\Recipe\Recipe as RecipeOrm;

class DbRecipeRepository implements RecipeRepository
{
    /**
     * @param Recipe $recipe
     * @return void
     * @see RecipeRepository::save()
     */
    public function save(Recipe $recipe): void
    {
        $recipeOrm = new RecipeOrm();
        $recipe->provide($recipeOrm);
        $recipeOrm->save();
    }
}
