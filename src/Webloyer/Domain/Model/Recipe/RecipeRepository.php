<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Recipe;

/**
 * @codeCoverageIgnore
 */
interface RecipeRepository
{
    /**
     * @return RecipeId
     */
    public function nextId(): RecipeId;
    /**
     * @return Recipes
     */
    public function findAll(): Recipes;
    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Recipes
     */
    public function findAllByPage(?int $page, ?int $perPage): Recipes;
    /**
     * @param RecipeId $id
     * @return Recipe|null
     */
    public function findById(RecipeId $id): ?Recipe;
    /**
     * @param Recipe $recipe
     * @return void
     */
    public function remove(Recipe $recipe): void;
    /**
     * @param Recipe $recipe
     * @return void
     */
    public function save(Recipe $recipe): void;
}
