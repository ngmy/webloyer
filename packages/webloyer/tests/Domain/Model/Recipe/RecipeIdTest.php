<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Recipe;

use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use TestCase;

class RecipeIdTest extends TestCase
{
    public function test_Should_GetId()
    {
        $expectedResult = 1;

        $recipeId = $this->createRecipeId([
            'id' => $expectedResult,
        ]);

        $actualResult = $recipeId->id();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function test_Should_EqualsReturnTrue_When_OtherObjectIsEqualToThisOne()
    {
        $this->checkEquals(
            $this->createRecipeId(),
            $this->createRecipeId(),
            true
        );
    }

    public function test_Should_EqualsReturnFalse_When_OtherObjectIsNotEqualToThisOne()
    {
        $this->checkEquals(
            $this->createRecipeId(),
            $this->createRecipeId([
                'id' => 2,
            ]),
            false
        );
    }

    private function checkEquals($self, $other, $expectedResult)
    {
        $actualResult = $self->equals($other);

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function createRecipeId(array $params = [])
    {
        $id = 1;

        extract($params);

        return new RecipeId($id);
    }
}
