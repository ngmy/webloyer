<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Repositories\Recipe;

use Str;
use Webloyer\Domain\Model\Recipe;
use Webloyer\Infra\Db\Eloquents\Recipe\Recipe as RecipeOrm;

class DbRecipeRepository implements Recipe\RecipeRepository
{
    /**
     * @return Recipe\RecipeId
     * @see Recipe\RecipeRepository::nextId()
     */
    public function nextId(): Recipe\RecipeId
    {
        return new Recipe\RecipeId(Str::orderedUuid()->toString());
    }

    /**
     * @return Recipe\Recipes
     * @see Recipe\RecipeRepository::findAll()
     */
    public function findAll(): Recipe\Recipes
    {
        $recipeArray = RecipeOrm::orderBy('name')
            ->get()
            ->map(function (RecipeOrm $recipeOrm): Recipe\Recipe {
                return $recipeOrm->toEntity();
            })
            ->toArray();
        return new Recipe\Recipes(...$recipeArray);
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Recipe\Recipes
     * @see Recipe\RecipeRepository::findAllByPage()
     */
    public function findAllByPage(?int $page, ?int $perPage): Recipe\Recipes
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $recipeArray = RecipeOrm::orderBy('name')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (RecipeOrm $recipeOrm): Recipe\Recipe {
                return $recipeOrm->toEntity();
            })
            ->toArray();
        return new Recipe\Recipes(...$recipeArray);
    }

    /**
     * @param Recipe\RecipeId $id
     * @return Recipe\Recipe|null
     * @see Recipe\RecipeRepository::findById()
     */
    public function findById(Recipe\RecipeId $id): ?Recipe\Recipe
    {
        $recipeOrm = RecipeOrm::ofId($id->value())->first();
        if (is_null($recipeOrm)) {
            return null;
        }
        return $recipeOrm->toEntity();
    }

    /**
     * @param Recipe\Recipe $recipe
     * @return void
     * @see Recipe\RecipeRepository::remove()
     */
    public function remove(Recipe\Recipe $recipe): void
    {
        $recipeOrm = RecipeOrm::ofId($recipe->id())->first();
        if (is_null($recipeOrm)) {
            return;
        }
        $recipeOrm->delete();
    }

    /**
     * @param Recipe\Recipe $recipe
     * @return void
     * @see Recipe\RecipeRepository::save()
     */
    public function save(Recipe\Recipe $recipe): void
    {
        $recipeOrm = RecipeOrm::firstOrNew(['uuid' => $recipe->id()]);
        $recipe->provide($recipeOrm);
        $recipeOrm->save();
    }
}
