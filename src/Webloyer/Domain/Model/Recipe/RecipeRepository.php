<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

/**
 * @codeCoverageIgnore
 */
interface RecipeRepository
{
    /**
     * @param Recipe $recipe
     * @return void
     */
    public function save(Recipe $recipe): void;
}
