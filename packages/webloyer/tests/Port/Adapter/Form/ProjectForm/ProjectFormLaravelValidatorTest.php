<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ProjectForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ProjectForm\ProjectFormLaravelValidator;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Recipe as EloquentRecipe;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Server as EloquentServer;
use Tests\Helpers\EloquentFactory;
use TestCase;

class ProjectFormLaravelValidatorTest extends TestCase
{
    protected $useDatabase = true;

    private $projectFormLaravelValidator;

    public function setUp()
    {
        parent::setUp();

        $this->projectFormLaravelValidator = new ProjectFormLaravelValidator($this->app['validator']);
    }

    public function test_Should_FailToValidate_When_RecipeIdFieldIsMissing()
    {
        EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        $input = [
            'name'       => 'Project 1',
            'server_id'  => 1,
            'repository' => 'http://example.com',
            'stage'      => 'staging',
        ];

        $actualResult = $this->projectFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->projectFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_NameFieldIsMissing()
    {
        EloquentFactory::create(
            EloquentRecipe::class,
            [
                'name'        => 'Recipe 1',
                'description' => '',
                'body'        => '',
            ]
        );

        EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        $input = [
            'recipe_id'  => [1],
            'server_id'  => 1,
            'repository' => 'http://example.com',
            'stage'      => 'staging',
        ];

        $actualResult = $this->projectFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->projectFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_ServerIdFieldIsMissing()
    {
        EloquentFactory::create(
            EloquentRecipe::class,
            [
                'name'        => 'Recipe 1',
                'description' => '',
                'body'        => '',
            ]
        );

        $input = [
            'name'       => 'Project 1',
            'recipe_id'  => [1],
            'repository' => 'http://example.com',
            'stage'      => 'staging',
        ];

        $actualResult = $this->projectFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->projectFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_RepositoryFieldIsMissing()
    {
        EloquentFactory::create(
            EloquentRecipe::class,
            [
                'name'        => 'Recipe 1',
                'description' => '',
                'body'        => '',
            ]
        );

        EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        $input = [
            'name'      => 'Project 1',
            'recipe_id' => [1],
            'server_id' => 1,
            'stage'     => 'staging',
        ];

        $actualResult = $this->projectFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->projectFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_RepositoryFieldIsInvalidUrl()
    {
        EloquentFactory::create(
            EloquentRecipe::class,
            [
                'name'        => 'Recipe 1',
                'description' => '',
                'body'        => '',
            ]
        );

        EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        $input = [
            'name'       => 'Project 1',
            'recipe_id'  => [1],
            'server_id'  => 1,
            'repository' => 'invalid_url',
            'stage'      => 'staging',
        ];

        $actualResult = $this->projectFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->projectFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_FailToValidate_When_StageFieldIsMissing()
    {
        EloquentFactory::create(
            EloquentRecipe::class,
            [
                'name'        => 'Recipe 1',
                'description' => '',
                'body'        => '',
            ]
        );

        EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        $input = [
            'name'       => 'Project 1',
            'recipe_id'  => [1],
            'server_id'  => 1,
            'repository' => 'http://example.com',
        ];

        $actualResult = $this->projectFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->projectFormLaravelValidator->errors();

        $this->assertFalse($actualResult, 'Expected validation to fail.');
        $this->assertInstanceOf(MessageBag::class, $actualErrors);
    }

    public function test_Should_PassToValidate_When_NameFieldAndRecipeIdFieldAndServerIdFieldAndRepositoryFieldAndStageFieldAreValid()
    {
        EloquentFactory::create(
            EloquentRecipe::class,
            [
                'name'        => 'Recipe 1',
                'description' => '',
                'body'        => '',
            ]
        );

        EloquentFactory::create(
            EloquentServer::class,
            [
                'name'        => 'Server 1',
                'description' => '',
                'body'        => '',
            ]
        );

        $input = [
            'name'       => 'Project 1',
            'recipe_id'  => [1],
            'server_id'  => 1,
            'repository' => 'http://example.com',
            'stage'      => 'staging',
        ];

        $actualResult = $this->projectFormLaravelValidator->with($input)->passes();
        $actualErrors = $this->projectFormLaravelValidator->errors();

        $this->assertTrue($actualResult, 'Expected validation to succeed.');
        $this->assertEmpty($actualErrors);
    }
}
