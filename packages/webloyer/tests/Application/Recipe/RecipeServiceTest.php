<?php

namespace Ngmy\Webloyer\Webloyer\Application\Recipe;

use Mockery;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeRepositoryInterface;
use TestCase;
use Tests\Helpers\MockeryHelper;

class RecipeServiceTest extends TestCase
{
    use MockeryHelper;

    private $recipeService;

    private $recipeRepository;

    private $inputForGetRecipesByPage = [
        'page'    => 1,
        'perPage' => 10,
    ];

    private $inputForSaveRecipe = [
        'recipeId'           => 1,
        'name'               => '',
        'description'        => '',
        'body'               => '',
        'concurrencyVersion' => '',
    ];

    public function setUp()
    {
        parent::setUp();

        $this->recipeRepository = $this->mock(RecipeRepositoryInterface::class);
        $this->recipeService = new RecipeService(
            $this->recipeRepository
        );
    }

    public function test_Should_GetAllRecipes()
    {
        $expectedResult = true;
        $this->recipeRepository
            ->shouldReceive('allRecipes')
            ->withNoArgs()
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->recipeService->getAllRecipes();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetRecipesByPage_When_PageAndPerPageIsNotSpecified()
    {
        $this->checkGetRecipesByPage(null, null, 1, 10);
    }

    public function test_Should_GetRecipesByPage_When_PageAndPerPageIsSpecified()
    {
        $this->checkGetRecipesByPage(2, 20, 2, 20);
    }

    public function test_Should_GetRecipeById()
    {
        $recipeId = 1;
        $expectedResult = true;
        $this->recipeRepository
            ->shouldReceive('recipeOfId')
            ->with(Mockery::on(function ($arg) use ($recipeId) {
                return $arg == new RecipeId($recipeId);
            }))
            ->andReturn($expectedResult)
            ->once();

        $actualResult = $this->recipeService->getRecipeById($recipeId);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_SaveRecipe_When_RecipeIdIsNull()
    {
        $this->checkSaveRecipe(true, false);
    }

    public function test_Should_SaveRecipe_When_RecipeIdIsNotNullAndRecipeExists()
    {
        $this->checkSaveRecipe(false, true);
    }

    public function test_Should_SaveRecipe_When_RecipeIdIsNotNullAndRecipeNotExists()
    {
        $this->checkSaveRecipe(false, false);
    }

    public function test_Should_RemoveRecipe()
    {
        $recipeId = 1;
        $recipe = $this->mock(Recipe::class);
        $this->recipeRepository
            ->shouldReceive('recipeOfId')
            ->with(Mockery::on(function ($arg) use ($recipeId) {
                return $arg == new RecipeId($recipeId);
            }))
            ->once()
            ->andReturn($recipe);
        $this->recipeRepository
            ->shouldReceive('remove')
            ->with($recipe)
            ->once();

        $this->recipeService->removeRecipe($recipeId);

        $this->assertTrue(true);
    }

    private function checkGetRecipesByPage($inputPage, $inputPerPage, $expectedPage, $expectedPerPage)
    {
        $this->inputForGetRecipesByPage['page'] = $inputPage;
        $this->inputForGetRecipesByPage['perPage'] = $inputPerPage;

        $expectedResult = true;
        $this->recipeRepository
            ->shouldReceive('recipesOfPage')
            ->with($expectedPage, $expectedPerPage)
            ->once()
            ->andReturn($expectedResult);

        extract($this->inputForGetRecipesByPage);

        if (isset($page) && isset($perPage)) {
            $actualResult = $this->recipeService->getRecipesByPage($page, $perPage);
        } elseif (isset($page)) {
            $actualResult = $this->recipeService->getRecipesByPage($page);
        } else {
            $actualResult = $this->recipeService->getRecipesByPage();
        }

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function checkSaveRecipe($isNullInputRecipeId, $existsRecipe)
    {
        if ($isNullInputRecipeId) {
            $this->inputForSaveRecipe['recipeId'] = null;
        } else {
            $this->inputForSaveRecipe['recipeId'] = 1;
            if ($existsRecipe) {
                $recipe = $this->mock(Recipe::class);
                $recipe
                    ->shouldReceive('failWhenConcurrencyViolation')
                    ->with($this->inputForSaveRecipe['concurrencyVersion'])
                    ->once();
            } else {
                $recipe = null;
            }
            $this->recipeRepository
                ->shouldReceive('recipeOfId')
                ->with(Mockery::on(function ($arg) {
                    return $arg == new RecipeId($this->inputForSaveRecipe['recipeId']);
                }))
                ->once()
                ->andReturn($recipe);
        }

        $this->recipeRepository
            ->shouldReceive('save')
            ->with(Mockery::on(function ($arg) {
                extract($this->inputForSaveRecipe);
                $recipe = new Recipe(
                    new RecipeId($recipeId),
                    $name,
                    $description,
                    $body,
                    [],
                    null,
                    null
                );
                return $arg == $recipe;
            }))
            ->once();

        extract($this->inputForSaveRecipe);

        $this->recipeService->saveRecipe(
            $recipeId,
            $name,
            $description,
            $body,
            $concurrencyVersion
        );

        $this->assertTrue(true);
    }
}
