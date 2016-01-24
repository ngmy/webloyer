<?php

use App\Services\Form\Project\ProjectForm;

class ProjectFormTest extends TestCase
{
    use Tests\Helpers\MockeryHelper;

    protected $mockValidator;

    protected $mockProjectRepository;

    public function setUp()
    {
        parent::setUp();

        $this->mockValidator = $this->mock('App\Services\Validation\ValidableInterface');
        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
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

        $this->mockProjectRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn(true);

        $input = [
            'recipe_id_order' => '3,1,2',
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
        ];

        $form = new ProjectForm($this->mockValidator, $this->mockProjectRepository);
        $result = $form->save($input);

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

        $this->mockProjectRepository
            ->shouldReceive('update')
            ->once()
            ->andReturn(true);

        $input = [
            'recipe_id_order' => '3,1,2',
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
            ->andReturn(new Illuminate\Support\MessageBag);

        $form = new ProjectForm($this->mockValidator, $this->mockProjectRepository);
        $result = $form->errors();

        $this->assertEmpty($result);
    }
}
