<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

class Recipes
{
    /** @var list<Recipe> */
    private $recipes;

    /**
     * @param Recipe ...$recipes
     * @return void
     */
    public function __construct(Recipe ...$recipes)
    {
        $this->recipes = $recipes;
    }

    /**
     * @return list<Recipe>
     */
    public function toArray(): array
    {
        return $this->recipes;
    }

    public function bodies(): RecipeBodies
    {
        return RecipeBodies::of(...array_map(function (Recipe $recipe): string {
            return $recipe->body();
        }, $this->recipes));
    }
}
