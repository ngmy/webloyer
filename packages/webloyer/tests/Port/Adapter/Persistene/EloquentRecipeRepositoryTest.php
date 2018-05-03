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
        $recipe = $this->createRecipe([
            'createdAt' => '2018-04-30 12:00:00',
            'updatedAt' => '2018-04-30 12:00:00',
        ]);

        $createdEloquentRecipe = EloquentFactory::create(EloquentRecipe::class, [
            'name'        => $recipe->name(),
            'description' => $recipe->description(),
            'body'        => $recipe->body(),
            'created_at'  => $recipe->createdAt(),
            'updated_at'  => $recipe->updatedAt(),
        ]);

        $eloquentRecipeRepository = $this->createEloquentRecipeRepository();
        $expectedResult = $eloquentRecipeRepository->toEntity($createdEloquentRecipe);

        $actualResult = $eloquentRecipeRepository->recipeOfId($expectedResult->recipeId());

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAllRecipes()
    {
        $recipes = [
            $this->createRecipe([
                'name'      => 'Recipe 1',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createRecipe([
                'name'      => 'Recipe 2',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createRecipe([
                'name'      => 'Recipe 3',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createRecipe([
                'name'      => 'Recipe 4',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createRecipe([
                'name'      => 'Recipe 5',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
        ];
        $page = 1;
        $limit = 10;

        $createdEloquentRecipes = EloquentFactory::createList(EloquentRecipe::class, array_map(function ($recipe) {
            return [
                'name'        => $recipe->name(),
                'description' => $recipe->description(),
                'body'        => $recipe->body(),
                'created_at'  => $recipe->createdAt(),
                'updated_at'  => $recipe->updatedAt(),
            ];
        }, $recipes));

        $eloquentRecipeRepository = $this->createEloquentRecipeRepository();

        $expectedResult = (new Collection(array_map(function ($eloquentRecipe) use ($eloquentRecipeRepository) {
                return $eloquentRecipeRepository->toEntity($eloquentRecipe);
            }, $createdEloquentRecipes)))->all();

        $actualResult = $eloquentRecipeRepository->allRecipes();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRecipesOfPage()
    {
        $recipes = [
            $this->createRecipe([
                'name'      => 'Recipe 1',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createRecipe([
                'name'      => 'Recipe 2',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createRecipe([
                'name'      => 'Recipe 3',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createRecipe([
                'name'      => 'Recipe 4',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
            $this->createRecipe([
                'name'      => 'Recipe 5',
                'createdAt' => '2018-04-30 12:00:00',
                'updatedAt' => '2018-04-30 12:00:00',
            ]),
        ];
        $page = 1;
        $limit = 10;

        $createdEloquentRecipes = EloquentFactory::createList(EloquentRecipe::class, array_map(function ($recipe) {
            return [
                'name'        => $recipe->name(),
                'description' => $recipe->description(),
                'body'        => $recipe->body(),
                'created_at'  => $recipe->createdAt(),
                'updated_at'  => $recipe->updatedAt(),
            ];
        }, $recipes));

        $eloquentRecipeRepository = $this->createEloquentRecipeRepository();

        $createdRecipes = new Collection(array_map(function ($eloquentRecipe) use ($eloquentRecipeRepository) {
                return $eloquentRecipeRepository->toEntity($eloquentRecipe);
            }, $createdEloquentRecipes));

        $expectedResult = new LengthAwarePaginator(
            $createdRecipes,
            $createdRecipes->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );

        $actualResult = $eloquentRecipeRepository->recipesOfPage();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_CreateNewRecipe()
    {
        $newRecipe = $this->createRecipe([
            'name' => 'some name',
            'description' => 'some desctiption.',
            'body' => 'some body.',
        ]);
        $eloquentRecipeRepository = $this->createEloquentRecipeRepository();

        $returnedRecipe = $eloquentRecipeRepository->save($newRecipe);

        $createdEloquentRecipe = EloquentRecipe::find($returnedRecipe->RecipeId()->id());

        $this->assertEquals($newRecipe->name(), $createdEloquentRecipe->name);
        $this->assertEquals($newRecipe->description(), $createdEloquentRecipe->description);
        $this->assertEquals($newRecipe->body(), $createdEloquentRecipe->body);

        $this->assertEquals($newRecipe->name(), $returnedRecipe->name());
        $this->assertEquals($newRecipe->description(), $returnedRecipe->description());
        $this->assertEquals($newRecipe->body(), $returnedRecipe->body());

        $this->assertEquals($createdEloquentRecipe->created_at, $returnedRecipe->createdAt());
        $this->assertEquals($createdEloquentRecipe->updated_at, $returnedRecipe->createdAt());
    }

    public function test_Should_UpdateExistingRecipe()
    {
        $eloquentRecipeShouldBeUpdated = EloquentFactory::create(EloquentRecipe::class, [
            'name'        => 'some name 1',
            'description' => 'some description 1',
            'body'        => 'some body 1',
        ]);

        $eloquentRecipeRepository = $this->createEloquentRecipeRepository();

        $newRecipe = $this->createRecipe([
            'recipeId' => $eloquentRecipeShouldBeUpdated->id,
            'name'        => 'some name 2',
            'description' => 'some description 2',
            'body'        => 'some body 2',
        ]);

        $returnedRecipe = $eloquentRecipeRepository->save($newRecipe);

        $updatedEloquentRecipe = EloquentRecipe::find($eloquentRecipeShouldBeUpdated->id);

        $this->assertEquals($newRecipe->name(), $updatedEloquentRecipe->name);
        $this->assertEquals($newRecipe->description(), $updatedEloquentRecipe->description);
        $this->assertEquals($newRecipe->body(), $updatedEloquentRecipe->body);

        $this->assertEquals($newRecipe->name(), $returnedRecipe->name());
        $this->assertEquals($newRecipe->description(), $returnedRecipe->description());
        $this->assertEquals($newRecipe->body(), $returnedRecipe->body());

        $this->assertEquals($updatedEloquentRecipe->created_at, $returnedRecipe->createdAt());
        $this->assertEquals($updatedEloquentRecipe->updated_at, $returnedRecipe->createdAt());
    }

    public function test_Should_DeleteExistingRecipe()
    {
        $eloquentRecipeShouldBeDeleted = EloquentFactory::create(EloquentRecipe::class, [
            'name'        => 'some name',
            'description' => 'some description',
            'body'        => 'some body',
        ]);

        $eloquentRecipeRepository = $this->createEloquentRecipeRepository();

        $eloquentRecipeRepository->remove($eloquentRecipeRepository->toEntity($eloquentRecipeShouldBeDeleted));

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
