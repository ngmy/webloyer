<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\EloquentRecipeRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Recipe as EloquentRecipe;
use Tests\Helpers\EloquentFactory;
use TestCase;

class EloquentRecipeRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetRecipeOfId()
    {
        $createdEloquentRecipe = EloquentFactory::create(EloquentRecipe::class, [
            'created_at' => '2018-04-30 12:00:00',
            'updated_at' => '2018-04-30 12:00:00',
        ]);
        $expectedResult = $createdEloquentRecipe->toEntity();

        $actualResult = $this->createEloquentRecipeRepository()->recipeOfId($expectedResult->recipeId());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAllRecipes()
    {
        $createdEloquentRecipes = EloquentFactory::createList(EloquentRecipe::class, [
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
        ]);
        $expectedResult = (new Collection(array_map(function ($eloquentRecipe) {
            return $eloquentRecipe->toEntity();
        }, $createdEloquentRecipes)))->all();

        $actualResult = $this->createEloquentRecipeRepository()->allRecipes();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRecipesOfPage()
    {
        $createdEloquentRecipes = EloquentFactory::createList(EloquentRecipe::class, [
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
            [
                'created_at' => '2018-04-30 12:00:00',
                'updated_at' => '2018-04-30 12:00:00',
            ],
        ]);
        $createdRecipes = new Collection(array_map(function ($eloquentRecipe) {
            return $eloquentRecipe->toEntity();
        }, $createdEloquentRecipes));
        $page = 1;
        $limit = 10;
        $expectedResult = new LengthAwarePaginator(
            $createdRecipes,
            $createdRecipes->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );

        $actualResult = $this->createEloquentRecipeRepository()->recipesOfPage();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_CreateNewRecipe()
    {
        $newRecipe = $this->createRecipe();

        $returnedRecipe = $this->createEloquentRecipeRepository()->save($newRecipe);

        $createdEloquentRecipe = EloquentRecipe::find($returnedRecipe->RecipeId()->id());

        $this->assertEquals($newRecipe->name(), $createdEloquentRecipe->name);
        $this->assertEquals($newRecipe->description(), $createdEloquentRecipe->description);
        $this->assertEquals($newRecipe->body(), $createdEloquentRecipe->body);

        $this->assertEquals($newRecipe->name(), $returnedRecipe->name());
        $this->assertEquals($newRecipe->description(), $returnedRecipe->description());
        $this->assertEquals($newRecipe->body(), $returnedRecipe->body());

        $this->assertEquals($createdEloquentRecipe->created_at, $returnedRecipe->createdAt());
        $this->assertEquals($createdEloquentRecipe->updated_at, $returnedRecipe->updatedAt());
    }

    public function test_Should_UpdateExistingRecipe()
    {
        $eloquentRecipeShouldBeUpdated = EloquentFactory::create(EloquentRecipe::class);

        $newRecipe = $this->createRecipe([
            'recipeId'    => $eloquentRecipeShouldBeUpdated->id,
            'name'        => 'new name',
            'description' => 'new description',
            'body'        => 'new body',
        ]);

        $returnedRecipe = $this->createEloquentRecipeRepository()->save($newRecipe);

        $updatedEloquentRecipe = EloquentRecipe::find($eloquentRecipeShouldBeUpdated->id);

        $this->assertEquals($newRecipe->name(), $updatedEloquentRecipe->name);
        $this->assertEquals($newRecipe->description(), $updatedEloquentRecipe->description);
        $this->assertEquals($newRecipe->body(), $updatedEloquentRecipe->body);

        $this->assertEquals($newRecipe->name(), $returnedRecipe->name());
        $this->assertEquals($newRecipe->description(), $returnedRecipe->description());
        $this->assertEquals($newRecipe->body(), $returnedRecipe->body());

        $this->assertEquals($updatedEloquentRecipe->created_at, $returnedRecipe->createdAt());
        $this->assertEquals($updatedEloquentRecipe->updated_at, $returnedRecipe->updatedAt());
    }

    public function test_Should_DeleteExistingRecipe()
    {
        $eloquentRecipeShouldBeDeleted = EloquentFactory::create(EloquentRecipe::class);

        $this->createEloquentRecipeRepository()->remove($eloquentRecipeShouldBeDeleted->toEntity());

        $deletedEloquentRecipe = EloquentRecipe::find($eloquentRecipeShouldBeDeleted->id);

        $this->assertNull($deletedEloquentRecipe);
    }

    private function createRecipe(array $params = [])
    {
        $recipeId = null;
        $name = '';
        $description = '';
        $body = '';
        $afferentProjectIds = [];
        $createdAt = null;
        $updatedAt = null;

        extract($params);

        return new Recipe(
            new RecipeId($recipeId),
            $name,
            $description,
            $body,
            array_map(function ($projectId) {
                return new ProjectId($projectId);
            }, $afferentProjectIds),
            new Carbon($createdAt),
            new Carbon($updatedAt)
        );
    }

    private function createEloquentRecipeRepository(array $params = [])
    {
        extract($params);

        return new EloquentRecipeRepository(new EloquentRecipe());
    }
}
