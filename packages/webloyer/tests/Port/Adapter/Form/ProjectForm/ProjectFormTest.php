<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ProjectForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ProjectForm\ProjectForm;
use Tests\Helpers\MockeryHelper;
use TestCase;

class ProjectFormTest extends TestCase
{
    use MockeryHelper;

    private $mockValidator;

    private $mockProjectService;

    public function setUp()
    {
        parent::setUp();

        $this->mockValidator = $this->mock(ValidableInterface::class);
        $this->mockProjectService = $this->mock(ProjectService::class);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_SucceedToSave_When_ValidationPassesAndDaysToKeepLastDeploymentIsTrue()
    {
        $projectForm = new ProjectForm($this->mockValidator, $this->mockProjectService);

        $this->mockValidator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->mockProjectService
            ->shouldReceive('saveProject');

        $input = $this->getInputToSave();
        $input['recipe_id_order'] = '1,2,3';
        $input['keep_last_deployment'] = true;

        $result = $projectForm->save($input);

        $this->assertTrue($result, 'Expected save to succeed.');
    }

    public function test_Should_SucceedToSave_When_ValidationPassesAndDaysToKeepLastDeploymentIsFalse()
    {
        $projectForm = new ProjectForm($this->mockValidator, $this->mockProjectService);

        $this->mockValidator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->mockProjectService
            ->shouldReceive('saveProject');

        $input = $this->getInputToSave();
        $input['recipe_id_order'] = '1,2,3';
        $input['keep_last_deployment'] = false;

        $result = $projectForm->save($input);

        $this->assertTrue($result, 'Expected save to succeed.');
    }

    public function test_Should_FailToSave_When_ValidationFails()
    {
        $projectForm = new ProjectForm($this->mockValidator, $this->mockProjectService);

        $this->mockValidator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $input = $this->getInputToSave();
        $input['recipe_id_order'] = '1,2,3';

        $result = $projectForm->save($input);

        $this->assertFalse($result, 'Expected save to fail.');
    }

    public function test_Should_SucceedToUpdate_When_ValidationPassesAndDaysToKeepLastDeploymentIsTrue()
    {
        $projectForm = new ProjectForm($this->mockValidator, $this->mockProjectService);

        $this->mockValidator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->mockProjectService
            ->shouldReceive('saveProject');

        $input = $this->getInputToUpdate();
        $input['recipe_id_order'] = '1,2,3';
        $input['keep_last_deployment'] = true;

        $result = $projectForm->update($input);

        $this->assertTrue($result, 'Expected update to succeed.');
    }

    public function test_Should_SucceedToUpdate_When_ValidationPassesAndDaysToKeepLastDeploymentIsFalse()
    {
        $projectForm = new ProjectForm($this->mockValidator, $this->mockProjectService);

        $this->mockValidator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->mockProjectService
            ->shouldReceive('saveProject');

        $input = $this->getInputToUpdate();
        $input['recipe_id_order'] = '1,2,3';
        $input['keep_last_deployment'] = false;

        $result = $projectForm->update($input);

        $this->assertTrue($result, 'Expected update to succeed.');
    }

    public function test_Should_FailToUpdate_When_ValidationFails()
    {
        $projectForm = new ProjectForm($this->mockValidator, $this->mockProjectService);

        $this->mockValidator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $input = $this->getInputToUpdate();
        $input['recipe_id_order'] = '1,2,3';

        $result = $projectForm->update($input);

        $this->assertFalse($result, 'Expected update to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $projectForm = new ProjectForm($this->mockValidator, $this->mockProjectService);

        $expectedResult = new MessageBag();

        $this->mockValidator
            ->shouldReceive('errors')
            ->andReturn($expectedResult);

        $actualResult = $projectForm->errors();

        $this->assertEquals($expectedResult, $actualResult);
    }

    private function getInputToSave()
    {
        return [
            'recipe_id_order'                   => null,
            'name'                              => null,
            'recipe_id'                         => null,
            'server_id'                         => null,
            'repository'                        => null,
            'stage'                             => null,
            'deploy_path'                       => null,
            'email_notification_recipient'      => null,
            'days_to_keep_deployments'          => null,
            'max_number_of_deployments_to_keep' => null,
            'keep_last_deployment'              => null,
            'github_webhook_secret'             => null,
            'github_webhook_user_id'            => null,
        ];
    }

    private function getInputToUpdate()
    {
        return [
            'recipe_id_order'                   => null,
            'id'                                => null,
            'name'                              => null,
            'recipe_id'                         => null,
            'server_id'                         => null,
            'repository'                        => null,
            'stage'                             => null,
            'deploy_path'                       => null,
            'email_notification_recipient'      => null,
            'days_to_keep_deployments'          => null,
            'max_number_of_deployments_to_keep' => null,
            'keep_last_deployment'              => null,
            'github_webhook_secret'             => null,
            'github_webhook_user_id'            => null,
            'concurrency_version'               => null,
        ];
    }
}
