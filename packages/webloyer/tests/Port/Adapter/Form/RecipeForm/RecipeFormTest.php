<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm\RecipeForm;
use Tests\Helpers\MockeryHelper;
use TestCase;

class RecipeFormTest extends TestCase
{
    use MockeryHelper;

    private $validator;

    private $recipeService;

    private $recipeForm;

    private $inputToSave = [
        'name'        => null,
        'description' => null,
        'body'        => null,
    ];

    private $inputToUpdate = [
        'id'                  => null,
        'name'                => null,
        'description'         => null,
        'body'                => null,
        'concurrency_version' => null,
    ];

    public function setUp()
    {
        parent::setUp();

        $this->validator = $this->mock(ValidableInterface::class);
        $this->recipeService = $this->mock(RecipeService::class);
        $this->recipeForm = new RecipeForm(
            $this->validator,
            $this->recipeService
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_SucceedToSave_When_ValidationPasses()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->recipeService
            ->shouldReceive('saveRecipe');

        $actualResult = $this->recipeForm->save($this->inputToSave);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToSave_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $actualResult = $this->recipeForm->save($this->inputToSave);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_SucceedToUpdate_When_ValidationPasses()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->recipeService
            ->shouldReceive('saveRecipe');

        $actualResult = $this->recipeForm->update($this->inputToUpdate);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToUpdate_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $actualResult = $this->recipeForm->update($this->inputToUpdate);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $expectedResult = new MessageBag();

        $this->validator
            ->shouldReceive('errors')
            ->andReturn($expectedResult);

        $actualResult = $this->recipeForm->errors();

        $this->assertEquals($expectedResult, $actualResult);
    }
}
