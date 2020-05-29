<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Recipe;

use Webloyer\Domain\Model\Recipe\{
    Recipe,
    Recipes,
};

class RecipesDtoDataTransformer implements RecipesDataTransformer
{
    /** @var Recipes */
    private $recipes;
    /** @var RecipeDtoDataTransformer */
    private $recipeDataTransformer;

    public function __construct(RecipeDtoDataTransformer $recipeDataTransformer)
    {
        $this->recipeDataTransformer = $recipeDataTransformer;
    }

    /**
     * @param Recipes $recipes
     * @return self
     */
    public function write(Recipes $recipes): self
    {
        $this->recipes = $recipes;
        return $this;
    }

    /**
     * @return array<int, object>
     */
    public function read()
    {
        return array_map(function (Recipe $recipe): object {
            return $this->recipeDataTransformer->write($recipe)->read();
        }, $this->recipes->toArray());
    }

    /**
     * @return RecipeDataTransformer
     */
    public function recipeDataTransformer(): RecipeDataTransformer
    {
        return $this->recipeDataTransformer;
    }
}
