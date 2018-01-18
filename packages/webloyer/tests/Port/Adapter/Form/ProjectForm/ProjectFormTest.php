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

    private $validator;

    private $projectService;

    private $projectForm;

    private $inputToSave = [
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

    private $inputToUpdate = [
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

    public function setUp()
    {
        parent::setUp();

        $this->validator = $this->mock(ValidableInterface::class);
        $this->projectService = $this->mock(ProjectService::class);
        $this->projectForm = new ProjectForm(
            $this->validator,
            $this->projectService
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_SucceedToSave_When_ValidationPassesAndDaysToKeepLastDeploymentIsSet()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->projectService
            ->shouldReceive('saveProject');

        $this->inputToSave['recipe_id_order'] = '1,2,3';
        $this->inputToSave['keep_last_deployment'] = true;

        $actualResult = $this->projectForm->save($this->inputToSave);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_SucceedToSave_When_ValidationPassesAndDaysToKeepLastDeploymentIsNotSet()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->projectService
            ->shouldReceive('saveProject');

        $this->inputToSave['recipe_id_order'] = '1,2,3';
        unset($this->inputToSave['keep_last_deployment']);;

        $actualResult = $this->projectForm->save($this->inputToSave);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToSave_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $this->inputToSave['recipe_id_order'] = '1,2,3';

        $actualResult = $this->projectForm->save($this->inputToSave);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_SucceedToUpdate_When_ValidationPassesAndDaysToKeepLastDeploymentIsSet()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->projectService
            ->shouldReceive('saveProject');

        $this->inputToUpdate['recipe_id_order'] = '1,2,3';
        $this->inputToUpdate['keep_last_deployment'] = true;

        $actualResult = $this->projectForm->update($this->inputToUpdate);

        $this->assertTrue($actualResult, 'Expected update to succeed.');
    }

    public function test_Should_SucceedToUpdate_When_ValidationPassesAndDaysToKeepLastDeploymentIsNotSet()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->projectService
            ->shouldReceive('saveProject');

        $this->inputToUpdate['recipe_id_order'] = '1,2,3';
        unset($this->inputToUpdate['keep_last_deployment']);

        $actualResult = $this->projectForm->update($this->inputToUpdate);

        $this->assertTrue($actualResult, 'Expected update to succeed.');
    }

    public function test_Should_FailToUpdate_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $this->inputToUpdate['recipe_id_order'] = '1,2,3';

        $actualResult = $this->projectForm->update($this->inputToUpdate);

        $this->assertFalse($actualResult, 'Expected update to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $expectedResult = new MessageBag();

        $this->validator
            ->shouldReceive('errors')
            ->andReturn($expectedResult);

        $actualResult = $this->projectForm->errors();

        $this->assertEquals($expectedResult, $actualResult);
    }
}
