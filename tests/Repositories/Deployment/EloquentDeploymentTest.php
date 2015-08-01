<?php

use App\Repositories\Deployment\EloquentDeployment;

use Tests\Helpers\Factory;

class EloquentDeploymentTest extends TestCase {

	protected $useDatabase = true;

	public function test_Should_GetDeploymentById()
	{
		$arrangedUser = Factory::create('App\Models\User', [
			'name'     => 'User 1',
			'email'    => 'user1@example.com',
			'password' => 'password',
		]);
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedServer = Factory::create('App\Models\Server', [
			'name'        => 'Server 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'server_id' => $arrangedServer->id,
			'stage'     => 'staging',
		]);
		$arrangedDeployment = Factory::create('App\Models\Deployment', [
			'project_id' => $arrangedProject->id,
			'task'       => 'deploy',
			'user_id'    => $arrangedUser->id,
		]);

		$deploymentRepository = new EloquentDeployment(new App\Models\Deployment);
		$foundDeployment = $deploymentRepository->byId($arrangedDeployment->id);

		$this->assertEquals($arrangedProject->id, $foundDeployment->project_id);
		$this->assertEquals('deploy', $foundDeployment->task);
		$this->assertEquals($arrangedUser->id, $foundDeployment->user_id);
		$this->assertEquals($arrangedUser->id, $foundDeployment->user->id);
		$this->assertEquals($arrangedUser->name, $foundDeployment->user->name);
		$this->assertEquals($arrangedUser->email, $foundDeployment->user->email);
		$this->assertEquals($arrangedUser->password, $foundDeployment->user->password);
	}

	public function test_Should_GetDeploymentByProjectIdAndNumber()
	{
		$arrangedUser = Factory::create('App\Models\User', [
			'name'     => 'User 1',
			'email'    => 'user1@example.com',
			'password' => 'password',
		]);
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedServer = Factory::create('App\Models\Server', [
			'name'        => 'Server 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'server_id' => $arrangedServer->id,
			'stage'     => 'staging',
		]);
		$arrangedDeployment = Factory::create('App\Models\Deployment', [
			'project_id' => $arrangedProject->id,
			'task'       => 'deploy',
			'user_id'    => $arrangedUser->id,
		]);

		$deploymentRepository = new EloquentDeployment(new App\Models\Deployment);
		$foundDeployment = $deploymentRepository->byProjectIdAndNumber($arrangedProject->id, $arrangedDeployment->number);

		$this->assertEquals($arrangedProject->id, $foundDeployment->project_id);
		$this->assertEquals('deploy', $foundDeployment->task);
		$this->assertEquals($arrangedUser->id, $foundDeployment->user_id);
		$this->assertEquals($arrangedUser->id, $foundDeployment->user->id);
		$this->assertEquals($arrangedUser->name, $foundDeployment->user->name);
		$this->assertEquals($arrangedUser->email, $foundDeployment->user->email);
		$this->assertEquals($arrangedUser->password, $foundDeployment->user->password);
	}

	public function test_Should_GetDeploymentsByProjectId()
	{
		$arrangedUser = Factory::create('App\Models\User', [
			'name'     => 'User 1',
			'email'    => 'user1@example.com',
			'password' => 'password',
		]);
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedServer = Factory::create('App\Models\Server', [
			'name'        => 'Server 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'server_id' => $arrangedServer->id,
			'stage'     => 'staging',
		]);
		Factory::createList('App\Models\Deployment', [
			['project_id' => $arrangedProject->id, 'number' => 1, 'task' => 'deploy', 'user_id' => $arrangedUser->id],
			['project_id' => $arrangedProject->id, 'number' => 2, 'task' => 'deploy', 'user_id' => $arrangedUser->id],
			['project_id' => $arrangedProject->id, 'number' => 3, 'task' => 'deploy', 'user_id' => $arrangedUser->id],
			['project_id' => $arrangedProject->id, 'number' => 4, 'task' => 'deploy', 'user_id' => $arrangedUser->id],
			['project_id' => $arrangedProject->id, 'number' => 5, 'task' => 'deploy', 'user_id' => $arrangedUser->id],
		]);

		$deploymentRepository = new EloquentDeployment(new App\Models\Deployment);
		$foundDeployments = $deploymentRepository->byProjectId($arrangedProject->id);

		$this->assertCount(5, $foundDeployments->items());
	}

	public function test_Should_CreateNewDeployment()
	{
		$arrangedUser = Factory::create('App\Models\User', [
			'name'     => 'User 1',
			'email'    => 'user1@example.com',
			'password' => 'password',
		]);
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedServer = Factory::create('App\Models\Server', [
			'name'        => 'Server 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'server_id' => $arrangedServer->id,
			'stage'     => 'staging',
		]);
		$arrangedMaxDeployment = Factory::create('App\Models\MaxDeployment', [
			'project_id' => 1,
		]);

		$deploymentRepository = new EloquentDeployment(new App\Models\Deployment);

		$returnedDeployment = $deploymentRepository->create([
			'project_id' => $arrangedProject->id,
			'task'       => 'deploy',
			'user_id'    => $arrangedUser->id,
		]);

		$deployment = new App\Models\Deployment;
		$createdDeployment = $deployment->find($returnedDeployment->id);

		$this->assertEquals($arrangedProject->id, $createdDeployment->project_id);
		$this->assertEquals('deploy', $createdDeployment->task);
		$this->assertEquals($arrangedUser->id, $createdDeployment->user_id);
		$this->assertEquals($arrangedUser->id, $createdDeployment->user->id);
		$this->assertEquals($arrangedUser->name, $createdDeployment->user->name);
		$this->assertEquals($arrangedUser->email, $createdDeployment->user->email);
		$this->assertEquals($arrangedUser->password, $createdDeployment->user->password);
	}

	public function test_Should_UpdateExistingDeployment()
	{
		$arrangedUser = Factory::create('App\Models\User', [
			'name'     => 'User 1',
			'email'    => 'user1@example.com',
			'password' => 'password',
		]);
		$arrangedRecipe = Factory::create('App\Models\Recipe', [
			'name'        => 'Recipe 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedServer = Factory::create('App\Models\Server', [
			'name'        => 'Server 1',
			'description' => '',
			'body'        => '',
		]);
		$arrangedProject = Factory::create('App\Models\Project', [
			'name'      => 'Project 1',
			'recipe_id' => $arrangedRecipe->id,
			'server_id' => $arrangedServer->id,
			'stage'     => 'staging',
		]);
		$arrangedDeployment = Factory::create('App\Models\Deployment', [
			'project_id' => $arrangedProject->id,
			'task'       => 'deploy',
			'user_id'    => $arrangedUser->id,
		]);

		$deploymentRepository = new EloquentDeployment(new App\Models\Deployment);
		$deploymentRepository->update([
			'id'      => $arrangedDeployment->id,
			'status'  => 0,
			'message' => 'Message',
		]);

		$deployment = new App\Models\Deployment;
		$updatedDeployment = $deployment->find($arrangedDeployment->id);

		$this->assertEquals(0, $updatedDeployment->status);
		$this->assertEquals('Message', $updatedDeployment->message);
	}

}
