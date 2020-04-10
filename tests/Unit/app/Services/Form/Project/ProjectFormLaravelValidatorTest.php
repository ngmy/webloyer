<?php

namespace Tests\Unit\app\Services\Form\Project;

use App\Models\Recipe;
use App\Models\Server;
use App\Services\Form\Project\ProjectFormLaravelValidator;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class ProjectFormLaravelValidatorTest extends TestCase
{
    protected $useDatabase = true;

    public function testShouldFailToValidateWhenRecipeIdFieldIsMissing()
    {
        $server = factory(Server::class)->create();

        $input = [
            'name'       => 'Project 1',
            'server_id'  => $server->id,
            'repository' => 'http://example.com',
            'stage'      => 'staging',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenNameFieldIsMissing()
    {
        $recipe = factory(Recipe::class)->create();
        $server = factory(Server::class)->create();

        $input = [
            'recipe_id'  => [$recipe->id],
            'server_id'  => $server->id,
            'repository' => 'http://example.com',
            'stage'      => 'staging',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenServerIdFieldIsMissing()
    {
        $recipe = factory(Recipe::class)->create();

        $input = [
            'name'       => 'Project 1',
            'recipe_id'  => [$recipe->id],
            'repository' => 'http://example.com',
            'stage'      => 'staging',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenRepositoryFieldIsMissing()
    {
        $recipe = factory(Recipe::class)->create();
        $server = factory(Server::class)->create();

        $input = [
            'name'      => 'Project 1',
            'recipe_id' => [$recipe->id],
            'server_id' => $server->id,
            'stage'     => 'staging',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenRepositoryFieldIsInvalidUrl()
    {
        $recipe = factory(Recipe::class)->create();
        $server = factory(Server::class)->create();

        $input = [
            'name'       => 'Project 1',
            'recipe_id'  => [$recipe->id],
            'server_id'  => $server->id,
            'repository' => 'invalid_url',
            'stage'      => 'staging',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldFailToValidateWhenStageFieldIsMissing()
    {
        $recipe = factory(Recipe::class)->create();
        $server = factory(Server::class)->create();

        $input = [
            'name'       => 'Project 1',
            'recipe_id'  => [$recipe->id],
            'server_id'  => $server->id,
            'repository' => 'http://example.com',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertFalse($result, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $errors);
    }

    public function testShouldPassToValidateWhenNameFieldAndRecipeIdFieldAndServerIdFieldAndRepositoryFieldAndStageFieldAreValid()
    {
        $recipe = factory(Recipe::class)->create();
        $server = factory(Server::class)->create();

        $input = [
            'name'       => 'Project 1',
            'recipe_id'  => [$recipe->id],
            'server_id'  => $server->id,
            'repository' => 'http://example.com',
            'stage'      => 'staging',
        ];

        $sut = $this->makeSut();

        $result = $sut->with($input)->passes();
        $errors = $sut->errors();

        $this->assertTrue($result, 'Expected validation to succeed.');
        $this->assertEmpty($errors);
    }

    public function makeSut(): ProjectFormLaravelValidator
    {
        return new ProjectFormLaravelValidator($this->app['validator']);
    }
}
