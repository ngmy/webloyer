<?php

namespace Tests\Unit\app\Repositories\Project;

use App\Entities\ProjectAttribute\ProjectAttributeEntity;
use App\Models\MaxDeployment;
use App\Models\Project;
use App\Models\Recipe;
use App\Models\Server;
use App\Repositories\Project\EloquentProject;
use Tests\TestCase;

class EloquentProjectTest extends TestCase
{
    protected $useDatabase = true;

    /** @var EloquentProject */
    private $sut;

    public function test_Should_GetProjectById()
    {
        $server = factory(Server::class)->create();
        $project = factory(Project::class)->create([
            'server_id' => $server->id,
        ]);

        $actual = $this->sut->byId($project->id);

        $this->assertTrue($project->is($actual));
    }

    public function test_Should_GetProjectsByPage()
    {
        $server = factory(Server::class)->create();
        $i = 1;
        $projects = factory(Project::class, 5)->make([
            'server_id' => $server->id,
        ])->each(function (Project $project) use ($i) {
            $project->name = 'project' . $i++;
            $project->save();
        });

        $actual = $this->sut->byPage();

        $this->assertCount(5, $actual->items());
        $this->assertTrue($projects[0]->is($actual[0]));
        $this->assertTrue($projects[1]->is($actual[1]));
        $this->assertTrue($projects[2]->is($actual[2]));
        $this->assertTrue($projects[3]->is($actual[3]));
        $this->assertTrue($projects[4]->is($actual[4]));
    }

    public function test_Should_CreateNewProject()
    {
        $server = factory(Server::class)->create();

        $actual = $this->sut->create([
            'name'                 => 'project1',
            'server_id'            => $server->id,
            'repository'           => 'https://github.com/USERNAME/REPOSITORY.git',
            'attributes'           => new ProjectAttributeEntity(),
            'keep_last_deployment' => false,
        ]);

        $this->assertDatabaseHas('projects', $actual->toArray());
    }

    public function test_Should_UpdateExistingProject()
    {
        $server1 = factory(Server::class)->create();
        $server2 = factory(Server::class)->create();
        $project = factory(Project::class)->create([
            'server_id' => $server1->id,
        ]);

        $this->sut->update([
            'id'        => $project->id,
            'server_id' => $server2->id,
        ]);

        $this->assertDatabaseHas('projects', [
            'id'        => $project->id,
            'server_id' => $server2->id,
        ]);
    }

    public function test_Should_DeleteExistingProject()
    {
        $recipe = factory(Recipe::class)->create();
        $server = factory(Server::class)->create();
        $project = factory(Project::class)->create([
            'server_id' => $server->id,
        ]);
        $project->recipes()->sync([
            $recipe->id => [
                'recipe_order' => 1,
            ]
        ]);

        $this->sut->delete($project->id);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('project_recipe', ['project_id' => $project->id]);
    }

    /**
     * @before
     */
    public function setUpSut(): void
    {
        $this->sut = new EloquentProject(
            new Project(),
            new MaxDeployment()
        );
    }
}
