<?php

namespace Tests\Unit\app\Services\Form\Recipe;

use App\Services\Form\Recipe\RecipeFormLaravelValidator;

class RecipeFormLaravelValidatorTest extends TestCase
{
    public function test_Should_FailToValidate_When_NameFieldIsMissing()
    {
        $input = [
            'description' => '',
            'body'        => '<?php $x = 1;',
        ];

        $form = new RecipeFormLaravelValidator($this->app['validator']);
        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
    }

    public function test_Should_FailToValidate_When_BodyFieldIsMissing()
    {
        $input = [
            'name'        => 'Recipe 1',
            'description' => '',
        ];

        $form = new RecipeFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf('Illuminate\Support\MessageBag', $errors);
    }

    public function test_Should_PassToValidate_When_NameFieldAndBodyFieldAreValid()
    {
        $input = [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '<?php $x = 1;',
        ];

        $form = new RecipeFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
    }
}
