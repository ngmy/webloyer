<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

class Recipes
{
    /** @var array<int, Recipe> */
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
     * @return array<int, Recipe>
     */
    public function toArray(): array
    {
        return $this->recipes;
    }
}
