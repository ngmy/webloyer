<?php

namespace Tests\Unit\app\Services\Form\Recipe;

use App\Services\Form\Recipe\RecipeFormLaravelValidator;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class RecipeFormLaravelValidatorTest extends TestCase
{
    public function testShouldFailToValidateWhenNameFieldIsMissing()
    {
        $input = [
            'description' => '',
            'body'        => '<?php $x = 1;',
        ];

        $form = new RecipeFormLaravelValidator($this->app['validator']);
        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenBodyFieldIsMissing()
    {
        $input = [
            'name'        => 'Recipe 1',
            'description' => '',
        ];

        $form = new RecipeFormLaravelValidator($this->app['validator']);

        $result = $form->with($input)->passes();
        $errors = $form->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldPassToValidateWhenNameFieldAndBodyFieldAreValid()
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
