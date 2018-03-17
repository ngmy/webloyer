<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm\RecipeFormLaravelValidator;
use TestCase;

class RecipeFormLaravelValidatorTest extends TestCase
{
    private $recipeFormLaravelValidator;

    public function setUp()
    {
        parent::setUp();

        $this->recipeFormLaravelValidator = new RecipeFormLaravelValidator($this->app['validator']);
    }

    public function test_Should_FailToValidate_When_NameFieldIsMissing()
    {
        $input = [
            'description' => '',
            'body'        => '<?php $x = 1;',
        ];

        $actualResult = $this->recipeFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->recipeFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_BodyFieldIsMissing()
    {
        $input = [
            'name'        => 'Recipe 1',
            'description' => '',
        ];

        $actualResult = $this->recipeFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->recipeFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_PassToValidate_When_NameFieldAndBodyFieldAreValid()
    {
        $input = [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '<?php $x = 1;',
        ];

        $actualResult = $this->recipeFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->recipeFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }
}
