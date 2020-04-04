<?php

namespace Tests\Unit\app\Services\Form\Recipe;

use App\Repositories\Recipe\RecipeInterface;
use App\Services\Form\Recipe\RecipeForm;
use App\Services\Validation\ValidableInterface;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class RecipeFormTest extends TestCase
{
    protected $mockValidator;

    protected $mockRecipeRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockValidator = $this->mock(ValidableInterface::class);
        $this->mockRecipeRepository = $this->mock(RecipeInterface::class);
    }

    public function test_Should_SucceedToSave_When_ValidationPasses()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockRecipeRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn(true);

        $form = new RecipeForm($this->mockValidator, $this->mockRecipeRepository);
        $result = $form->save([]);

        $this->assertTrue($result, 'Expected save to succeed.');
    }

    public function test_Should_FailToSave_When_ValidationFails()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(false);

        $form = new RecipeForm($this->mockValidator, $this->mockRecipeRepository);
        $result = $form->save([]);

        $this->assertFalse($result, 'Expected save to fail.');
    }

    public function test_Should_SucceedToUpdate_When_ValidationPasses()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockRecipeRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $form = new RecipeForm($this->mockValidator, $this->mockRecipeRepository);
        $result = $form->update([]);

        $this->assertTrue($result, 'Expected update to succeed.');
    }

    public function test_Should_FailToUpdate_When_ValidationFails()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(false);

        $form = new RecipeForm($this->mockValidator, $this->mockRecipeRepository);
        $result = $form->update([]);

        $this->assertFalse($result, 'Expected update to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $this->mockValidator
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new MessageBag());

        $form = new RecipeForm($this->mockValidator, $this->mockRecipeRepository);
        $result = $form->errors();

        $this->assertEmpty($result);
    }
}
