<?php

namespace Tests\Unit\app\Services\Form\Project;

use App\Services\Form\Project\ProjectForm;
use Illuminate\Support\MessageBag;
use Tests\Helpers\MockeryHelper;
use Tests\TestCase;

class ProjectFormTest extends TestCase
{
    use MockeryHelper;

    protected $mockValidator;

    protected $mockProjectRepository;

    protected $mockProjectModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockValidator = $this->mock('App\Services\Validation\ValidableInterface');
        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
    }

    public function test_Should_SucceedToSaveAndNotAddProjectAttribute_When_ValidationPassesAndDeployPathFieldIsNotSpecified()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $project = $this->mockProjectModel
            ->shouldReceive('addMaxDeployment')
            ->once()
            ->shouldReceive('syncRecipes')
            ->once()
            ->mock();
        $this->mockProjectRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn($project);

        $input = [
            'recipe_id_order' => '3,1,2',
            'deploy_path'     => '',
        ];

        $form = new ProjectForm($this->mockValidator, $this->mockProjectRepository);
        $result = $form->save($input);

        $this->assertTrue($result, 'Expected save to succeed.');
    }

    public function test_Should_SucceedToSaveAndAddProjectAttribute_When_ValidationPassesAndDeployPathFieldIsSpecified()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $project = $this->mockProjectModel
            ->shouldReceive('addMaxDeployment')
            ->once()
            ->shouldReceive('syncRecipes')
            ->once()
            ->mock();
        $this->mockProjectRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn($project);

        $input = [
            'recipe_id_order' => '3,1,2',
            'deploy_path'     => '/home/www',
        ];

        $form = new ProjectForm($this->mockValidator, $this->mockProjectRepository);
        $result = $form->save($input);

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

        $input = [
            'recipe_id_order' => '3,1,2',
            'deploy_path'     => '',
        ];

        $form = new ProjectForm($this->mockValidator, $this->mockProjectRepository);
        $result = $form->save($input);

        $this->assertFalse($result, 'Expected save to fail.');
    }

    public function test_Should_SucceedToUpdateAndNotAddProjectAttribute_When_ValidationPassesAndDeployPathFieldIsNotSpecified()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $project = $this->mockProjectModel
            ->shouldReceive('syncRecipes')
            ->once()
            ->mock();
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);
        $this->mockProjectRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $input = [
            'id'              => $project->id,
            'recipe_id_order' => '3,1,2',
            'deploy_path'     => '',
        ];

        $form = new ProjectForm($this->mockValidator, $this->mockProjectRepository);
        $result = $form->update($input);

        $this->assertTrue($result, 'Expected update to succeed.');
    }

    public function test_Should_SucceedToUpdateAndAddProjectAttribute_When_ValidationPassesAndDeployPathFieldIsSpecified()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $project = $this->mockProjectModel
            ->shouldReceive('syncRecipes')
            ->once()
            ->mock();
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);
        $this->mockProjectRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $input = [
            'id'              => $project->id,
            'recipe_id_order' => '3,1,2',
            'deploy_path'     => '/home/www',
        ];

        $form = new ProjectForm($this->mockValidator, $this->mockProjectRepository);
        $result = $form->update($input);

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

        $input = [
            'recipe_id_order' => '3,1,2',
            'deploy_path'     => '',
        ];

        $form = new ProjectForm($this->mockValidator, $this->mockProjectRepository);
        $result = $form->update($input);

        $this->assertFalse($result, 'Expected update to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $this->mockValidator
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new MessageBag());

        $form = new ProjectForm($this->mockValidator, $this->mockProjectRepository);
        $result = $form->errors();

        $this->assertEmpty($result);
    }
}
