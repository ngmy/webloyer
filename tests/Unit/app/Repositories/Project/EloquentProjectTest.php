<?php

namespace Tests\Unit\app\Repositories\Project;

use App\Models\MaxDeployment;
use App\Models\Project;
use App\Models\Recipe;
use App\Models\Server;
use App\Repositories\Project\EloquentProject;
use Tests\Helpers\Factory;
use Tests\TestCase;

class EloquentProjectTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetProjectById()
    {
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);
        $arrangedProject = Factory::create(Project::class, [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);

        $projectRepository = new EloquentProject(
            new Project(),
            new MaxDeployment()
        );

        $foundProject = $projectRepository->byId($arrangedProject->id);

        $this->assertEquals('Project 1', $foundProject->name);
        $this->assertEquals($arrangedProject->server_id, $foundProject->server_id);
        $this->assertEquals('staging', $foundProject->stage);
    }

    public function test_Should_GetProjectsByPage()
    {
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);
        Factory::createList(Project::class, [
            ['name' => 'Project 1', 'server_id' => $arrangedServer->id, 'stage' => 'staging'],
            ['name' => 'Project 2', 'server_id' => $arrangedServer->id, 'stage' => 'staging'],
            ['name' => 'Project 3', 'server_id' => $arrangedServer->id, 'stage' => 'staging'],
            ['name' => 'Project 4', 'server_id' => $arrangedServer->id, 'stage' => 'staging'],
            ['name' => 'Project 5', 'server_id' => $arrangedServer->id, 'stage' => 'staging'],
        ]);

        $projectRepository = new EloquentProject(
            new Project(),
            new MaxDeployment()
        );

        $foundProjects = $projectRepository->byPage();

        $this->assertCount(5, $foundProjects->items());
    }

    public function test_Should_CreateNewProject()
    {
        $projectRepository = new EloquentProject(
            new Project(),
            new MaxDeployment()
        );

        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);
        $returnedProject = $projectRepository->create([
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);

        $project = new Project();
        $createdProject = $project->find($returnedProject->id);

        $this->assertEquals('Project 1', $createdProject->name);
        $this->assertEquals($arrangedServer->id, $createdProject->server_id);
        $this->assertEquals('staging', $createdProject->stage);
    }

    public function test_Should_UpdateExistingProject()
    {
        $arrangedRecipe = Factory::create(Recipe::class, [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);
        $arrangedProject = Factory::create(Project::class, [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);
        $arrangedProject->recipes()->sync([
            $arrangedRecipe->id => [
                'recipe_order' => 1,
            ]
        ]);

        $projectRepository = new EloquentProject(
            new Project(),
            new MaxDeployment()
        );
        $arrangedServer2 = Factory::create(Server::class, [
            'name'        => 'Server 2 ',
            'description' => '',
            'body'        => '',
        ]);
        $projectRepository->update([
            'id'        => $arrangedProject->id,
            'name'      => 'Project 2',
            'server_id' => $arrangedServer2->id,
            'stage'     => 'production',
        ]);

        $project = new Project();
        $updatedProject = $project->find($arrangedProject->id);

        $this->assertEquals('Project 2', $updatedProject->name);
        $this->assertEquals($arrangedRecipe->id, $updatedProject->recipes->first()->id);
        $this->assertEquals($arrangedServer2->id, $updatedProject->server_id);
        $this->assertEquals('production', $updatedProject->stage);
    }

    public function test_Should_DeleteExistingProject()
    {
        $arrangedRecipe = Factory::create(Recipe::class, [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);
        $arrangedServer = Factory::create(Server::class, [
            'name'        => 'Recipe 1',
            'description' => '',
            'body'        => '',
        ]);
        $arrangedProject = Factory::create(Project::class, [
            'name'      => 'Project 1',
            'server_id' => $arrangedServer->id,
            'stage'     => 'staging',
        ]);
        $arrangedProject->recipes()->sync([
            $arrangedRecipe->id => [
                'recipe_order' => 1,
            ]
        ]);

        $projectRepository = new EloquentProject(
            new Project(),
            new MaxDeployment()
        );
        $projectRepository->delete($arrangedProject->id);

        $project = new Project();
        $deletedProject = $project->find($arrangedProject->id);

        $this->assertNull($deletedProject);

        $updatedProjectRecipes = $arrangedProject->recipes;

        $this->assertEmpty($updatedProjectRecipes);
    }
}
