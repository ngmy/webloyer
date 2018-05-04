<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Recipe as EloquentRecipe;

class EloquentRecipeRepository implements RecipeRepositoryInterface
{
    private $eloquentRecipe;

    /**
     * Create a new repository instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Recipe $eloquentRecipe
     * @return void
     */
    public function __construct(EloquentRecipe $eloquentRecipe)
    {
        $this->eloquentRecipe = $eloquentRecipe;
    }

    public function allRecipes()
    {
        $eloquentRecipes = $this->eloquentRecipe->all();

        $recipes = $eloquentRecipes->map(function ($eloquentRecipe, $key) {
            return $eloquentRecipe->toEntity();
        })->all();

        return $recipes;
    }

    public function recipesOfPage($page = 1, $limit = 10)
    {
        $eloquentRecipes = $this->eloquentRecipe
            ->orderBy('name')
            ->get();

        $recipes = $eloquentRecipes
            ->slice($limit * ($page - 1), $limit)
            ->map(function ($eloquentRecipe, $key) {
                return $eloquentRecipe->toEntity();
            });

        return new LengthAwarePaginator(
            $recipes,
            $eloquentRecipes->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );
    }

    public function recipeOfId(RecipeId $recipeId)
    {
        $primaryKey = $recipeId->id();

        $eloquentRecipe = $this->eloquentRecipe->find($primaryKey);

        $recipe = $eloquentRecipe->toEntity();

        return $recipe;
    }

    public function remove(Recipe $recipe)
    {
        $eloquentRecipe = $this->toEloquent($recipe);

        $eloquentRecipe->delete();

        return true;
    }

    public function save(Recipe $recipe)
    {
        $eloquentRecipe = $this->toEloquent($recipe);

        $eloquentRecipe->save();

        $recipe = $eloquentRecipe->toEntity();

        return $recipe;
    }

    public function toEloquent(Recipe $recipe)
    {
        $primaryKey = $recipe->recipeId()->id();

        if (is_null($primaryKey)) {
            $eloquentRecipe = new EloquentRecipe();
        } else {
            $eloquentRecipe = $this->eloquentRecipe->find($primaryKey);
        }

        $eloquentRecipe->name = $recipe->name();
        $eloquentRecipe->description = $recipe->description();
        $eloquentRecipe->body = $recipe->body();

        return $eloquentRecipe;
    }
}
