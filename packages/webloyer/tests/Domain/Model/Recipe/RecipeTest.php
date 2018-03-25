<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Recipe;

use Carbon\Carbon;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use TestCase;

class RecipeTest extends TestCase
{
    public function test_Should_GetRecipeId()
    {
        $expectedResult = new RecipeId(1);

        $recipe = $this->createRecipe([
            'recipeId' => $expectedResult->id(),
        ]);

        $actualResult = $recipe->recipeId();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetName()
    {
        $expectedResult = 'some name';

        $recipe = $this->createRecipe([
            'name' => $expectedResult,
        ]);

        $actualResult = $recipe->name();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetDescription()
    {
        $expectedResult = 'some description';

        $recipe = $this->createRecipe([
            'description' => $expectedResult,
        ]);

        $actualResult = $recipe->description();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetBody()
    {
        $expectedResult = 'some body';

        $recipe = $this->createRecipe([
            'body' => $expectedResult,
        ]);

        $actualResult = $recipe->body();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAfferentProjectIds()
    {
        $expectedResult = [
            new ProjectId(1),
        ];

        $recipe = $this->createRecipe([
            'afferentProjectIds' => array_map(function ($projectId) {
                return $projectId->id();
            }, $expectedResult),
        ]);

        $actualResult = $recipe->afferentProjectIds();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetAfferentProjectsCount()
    {
        $afferentProjectIds = [
            new ProjectId(1),
        ];
        $expectedResult = count($afferentProjectIds);

        $recipe = $this->createRecipe([
            'afferentProjectIds' => array_map(function ($projectId) {
                return $projectId->id();
            }, $afferentProjectIds),
        ]);

        $actualResult = $recipe->afferentProjectsCount();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetCreatedAt()
    {
        $expectedResult = new Carbon('2018-03-18 00:00:00');

        $recipe = $this->createRecipe([
            'createdAt' => $expectedResult->format('Y-m-d H:i:s'),
        ]);

        $actualResult = $recipe->createdAt();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_GetUpdatedAt()
    {
        $expectedResult = new Carbon('2018-03-18 00:00:00');

        $recipe = $this->createRecipe([
            'updatedAt' => $expectedResult->format('Y-m-d H:i:s'),
        ]);

        $actualResult = $recipe->updatedAt();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createRecipe(),
            $this->createRecipe(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createRecipe(),
            $this->createRecipe([
                'recipeId' => 2,
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createRecipe(array $params = [])
    {
        $recipeId = 1;
        $name = '';
        $description = '';
        $body = '';
        $afferentProjectIds = [];
        $createdAt = '';
        $updatedAt = '';

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
}
