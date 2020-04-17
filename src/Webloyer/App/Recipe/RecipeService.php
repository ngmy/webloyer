<?php

declare(strict_types=1);

namespace Webloyer\App\Recipe;

use InvalidArgumentException;
use Webloyer\App\Recipe\Commands;
use Webloyer\Domain\Model\Recipe;

class RecipeService
{
    /** @var Recipe\RecipeRepository */
    private $recipeRepository;

    /**
     * @param Recipe\RecipeRepository $recipeRepository
     * @return void
     */
    public function __construct(Recipe\RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    /**
     * @return Recipe\Recipes
     */
    public function getAllRecipes(): Recipe\Recipes
    {
        return $this->recipeRepository->findAll();
    }

    /**
     * @param Commands\GetRecipesCommand $command
     * @return Recipe\Recipes
     */
    public function getRecipes(Commands\GetRecipesCommand $command): Recipe\Recipes
    {
        return $this->recipeRepository->findAllByPage($command->getPage(), $command->getPerPage());
    }

    /**
     * @param Commands\GetRecipeCommand $command
     * @return Recipe\Recipe
     */
    public function getRecipe(Commands\GetRecipeCommand $command): Recipe\Recipe
    {
        $id = new Recipe\RecipeId($command->getId());
        return $this->getNonNullRecipe($id);
    }

    /**
     * @param Commands\CreateRecipeCommand $command
     * @return void
     */
    public function createRecipe(Commands\CreateRecipeCommand $command): void
    {
        $recipe = Recipe\Recipe::of(
            $this->recipeRepository->nextId()->value(),
            $command->getName(),
            $command->getDescription(),
            $command->getBody()
        );
        $this->recipeRepository->save($recipe);
    }

    /**
     * @param Commands\UpdateRecipeCommand $command
     * @return void
     */
    public function updateRecipe(Commands\UpdateRecipeCommand $command): void
    {
        $id = new Recipe\RecipeId($command->getId());
        $name = new Recipe\RecipeName($command->getName());
        $description = new Recipe\RecipeDescription($command->getDescription());
        $body = new Recipe\RecipeBody($command->getBody());
        $recipe = $this->getNonNullRecipe($id)
            ->changeName($name)
            ->changeDescription($description)
            ->changeBody($body);
        $this->recipeRepository->save($recipe);
    }

    /**
     * @param Commands\DeleteRecipeCommand $command
     * @return void
     */
    public function deleteRecipe(Commands\DeleteRecipeCommand $command): void
    {
        $id = new Recipe\RecipeId($command->getId());
        $recipe = $this->getNonNullRecipe($id);
        $this->recipeRepository->remove($recipe);
    }

    /**
     * @param Recipe\RecipeId $id
     * @return Recipe\Recipe
     * @throws InvalidArgumentException
     */
    private function getNonNullRecipe(Recipe\RecipeId $id): Recipe\Recipe
    {
        $recipe = $this->recipeRepository->findById($id);
        if (is_null($recipe)) {
            throw new InvalidArgumentException(
                'Recipe does not exists.' . PHP_EOL .
                'Id: ' . $id->value()
            );
        }
        return $recipe;
    }
}
