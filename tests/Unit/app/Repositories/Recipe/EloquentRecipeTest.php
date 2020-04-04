<?php

namespace Tests\Unit\app\Repositories\Recipe;

use App\Models\Recipe;
use App\Repositories\Recipe\EloquentRecipe;
use Tests\Helpers\Factory;
use Tests\TestCase;

class EloquentRecipeTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetRecipeById()
    {
        $arrangedRecipe = Factory::create(Recipe::class, [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);

        $recipeRepository = new EloquentRecipe(new Recipe());

        $foundRecipe = $recipeRepository->byId($arrangedRecipe->id);

        $this->assertEquals('Recipe 1', $foundRecipe->name);
        $this->assertEquals('', $foundRecipe->description);
        $this->assertEquals('', $foundRecipe->body);
    }

    public function test_Should_GetRecipesByPage()
    {
        Factory::createList(Recipe::class, [
            ['name' => 'Recipe 1', 'description' => '', 'body' => ''],
            ['name' => 'Recipe 2', 'description' => '', 'body' => ''],
            ['name' => 'Recipe 3', 'description' => '', 'body' => ''],
            ['name' => 'Recipe 4', 'description' => '', 'body' => ''],
            ['name' => 'Recipe 5', 'description' => '', 'body' => ''],
        ]);

        $recipeRepository = new EloquentRecipe(new Recipe());

        $foundRecipes = $recipeRepository->byPage();

        $this->assertCount(5, $foundRecipes->items());
    }

    public function test_Should_CreateNewRecipe()
    {
        $recipeRepository = new EloquentRecipe(new Recipe());

        $returnedRecipe = $recipeRepository->create([
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);

        $recipe = new Recipe();
        $createdRecipe = $recipe->find($returnedRecipe->id);

        $this->assertEquals('Recipe 1', $createdRecipe->name);
        $this->assertEquals('', $createdRecipe->description);
        $this->assertEquals('', $createdRecipe->body);
    }

    public function test_Should_UpdateExistingRecipe()
    {
        $arrangedRecipe = Factory::create(Recipe::class, [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);

        $recipeRepository = new EloquentRecipe(new Recipe());

        $recipeRepository->update([
            'id'          => $arrangedRecipe->id,
            'name'        => 'Recipe 2',
            'description' => 'Description',
            'body'        => '<?php $x = 1;',
        ]);

        $recipe = new Recipe();
        $updatedRecipe = $recipe->find($arrangedRecipe->id);

        $this->assertEquals('Recipe 2', $updatedRecipe->name);
        $this->assertEquals('Description', $updatedRecipe->description);
        $this->assertEquals('<?php $x = 1;', $updatedRecipe->body);
    }

    public function test_Should_DeleteExistingRecipe()
    {
        $arrangedRecipe = Factory::create(Recipe::class, [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);

        $recipeRepository = new EloquentRecipe(new Recipe());

        $recipeRepository->delete($arrangedRecipe->id);

        $recipe = new Recipe();
        $deletedRecipe = $recipe->find($arrangedRecipe->id);

        $this->assertNull($deletedRecipe);
    }
}
