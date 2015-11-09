<?php

use App\Models\DeploymentPresenter;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

use Tests\Helpers\Factory;

class DeploymentPresenterTest extends TestCase {

	public function test_Should_ConvertStatusToHtmlSnippet_When_StatusIsOK()
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

		$converter = new AnsiToHtmlConverter;
		$deploymentPresenter = new DeploymentPresenter($deployment, $converter);

		$html = $deploymentPresenter->status();

		$this->assertEquals('<span class="glyphicon glyphicon-ok-circle green" aria-hidden="true"></span>', $html);
	}

	public function test_Should_ConvertStatusToHtmlSnippet_When_StatusIsNg()
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

		$converter = new AnsiToHtmlConverter;
		$deploymentPresenter = new DeploymentPresenter($deployment, $converter);

		$html = $deploymentPresenter->status();

		$this->assertEquals('<span class="glyphicon glyphicon-ban-circle red" aria-hidden="true"></span>', $html);
	}

	public function test_Should_ConvertStatusToHtmlSnippet_When_StatusIsUnknown()
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

		$converter = new AnsiToHtmlConverter;
		$deploymentPresenter = new DeploymentPresenter($deployment, $converter);

		$html = $deploymentPresenter->status();

		$this->assertEquals('<span></span>', $html);
	}

	public function test_Should_ConvertMessageToHtmlSnippet()
	{
		$deployment = Factory::build('App\Models\Deployment', [
			'id'         => 1,
			'project_id' => 1,
			'number'     => 1,
			'status'     => null,
			'task'       => 'deploy',
			'user_id'    => 1,
			'message'    => 'Message',
			'created_at' => new Carbon\Carbon,
			'updated_at' => new Carbon\Carbon,
			'user'       => new App\Models\User,
		]);

		$converter = new AnsiToHtmlConverter;
		$deploymentPresenter = new DeploymentPresenter($deployment, $converter);

		$html = $deploymentPresenter->message();

		$this->assertEquals('<span style="background-color: black; color: white">Message</span>', $html);
	}

}
