<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Recipe;

use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;

interface RecipeRepositoryInterface
{
    public function allRecipes();

    public function recipesOfPage($page = 1, $limit = 10);

    public function recipeOfId(RecipeId $recipeId);

    public function remove(Recipe $recipe);

    public function save(Recipe $recipe);
}
