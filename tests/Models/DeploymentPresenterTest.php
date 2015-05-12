<?php

use App\Models\DeploymentPresenter;

use Tests\Helpers\Factory;

class DeploymentPresenterTest extends TestCase {

	public function test_Should_ReturnHtmlSnippet_When_StatusIsOK()
	{
		$deployment = Factory::build('App\Models\Deployment', [
			'id'         => 1,
			'project_id' => 1,
			'number'     => 1,
			'status'     => 0,
			'task'       => 'deploy',
			'user_id'    => 1,
			'created_at' => new Carbon\Carbon,
			'updated_at' => new Carbon\Carbon,
			'user'       => new App\Models\User,
		]);

		$deploymentPresenter = new DeploymentPresenter($deployment);

		$html = $deploymentPresenter->status();

		$this->assertEquals('<span class="glyphicon glyphicon-ok-circle green" aria-hidden="true"></span>', $html);
	}

	public function test_Should_ReturnHtmlSnippet_When_StatusIsNg()
	{
		$deployment = Factory::build('App\Models\Deployment', [
			'id'         => 1,
			'project_id' => 1,
			'number'     => 1,
			'status'     => 1,
			'task'       => 'deploy',
			'user_id'    => 1,
			'created_at' => new Carbon\Carbon,
			'updated_at' => new Carbon\Carbon,
			'user'       => new App\Models\User,
		]);

		$deploymentPresenter = new DeploymentPresenter($deployment);

		$html = $deploymentPresenter->status();

		$this->assertEquals('<span class="glyphicon glyphicon-ban-circle red" aria-hidden="true"></span>', $html);
	}

	public function test_Should_ReturnHtmlSnippet_When_StatusIsUnknown()
	{
		$deployment = Factory::build('App\Models\Deployment', [
			'id'         => 1,
			'project_id' => 1,
			'number'     => 1,
			'status'     => null,
			'task'       => 'deploy',
			'user_id'    => 1,
			'created_at' => new Carbon\Carbon,
			'updated_at' => new Carbon\Carbon,
			'user'       => new App\Models\User,
		]);

		$deploymentPresenter = new DeploymentPresenter($deployment);

		$html = $deploymentPresenter->status();

		$this->assertEquals('<span></span>', $html);
	}

}
