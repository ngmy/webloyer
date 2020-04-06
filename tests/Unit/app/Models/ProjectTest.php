<?php

namespace Tests\Unit\app\Models;

use App\Models\Deployment;
use App\Models\Project;
use App\Models\Server;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetDeploymentsWhereCreatedAtBefore()
    {
        $user = factory(User::class)->create();
        $server = factory(Server::class)->create();
        $project = factory(Project::class)->create([
            'server_id' => $server->id,
        ]);
        $deployment1 = factory(Deployment::class)->create([
            'project_id' => $project->id,
            'number'     => 1,
            'user_id'    => $user->id,
            'created_at' => Carbon::create(2016, 8, 16, 0, 0, 0),
        ]);
        $deployment2 = factory(Deployment::class)->create([
            'project_id' => $project->id,
            'number'     => 2,
            'user_id'    => $user->id,
            'created_at' => Carbon::create(2016, 8, 16, 23, 59, 59),
        ]);
        $deployment3 = factory(Deployment::class)->create([
            'project_id' => $project->id,
            'number'     => 3,
            'user_id'    => $user->id,
            'created_at' => Carbon::create(2016, 8, 17, 0, 0, 0),
        ]);
        $deployment4 = factory(Deployment::class)->create([
            'project_id' => $project->id,
            'number'     => 4,
            'user_id'    => $user->id,
            'created_at' => Carbon::create(2016, 8, 17, 23, 59, 59),
        ]);

        $actual = $project->getDeploymentsWhereCreatedAtBefore(Carbon::create(2016, 8, 17, 0, 0, 0));

        $this->assertTrue($actual[0]->is($deployment2));
        $this->assertTrue($actual[1]->is($deployment1));
    }

    public function test_Should_GetDeploymentsWhereNumberBefore()
    {
        $user = factory(User::class)->create();
        $server = factory(Server::class)->create();
        $project = factory(Project::class)->create([
            'server_id' => $server->id,
        ]);
        $deployment1 = factory(Deployment::class)->create([
            'project_id' => $project->id,
            'number'     => 1,
            'user_id'    => $user->id,
        ]);
        $deployment2 = factory(Deployment::class)->create([
            'project_id' => $project->id,
            'number'     => 2,
            'user_id'    => $user->id,
        ]);
        $deployment3 = factory(Deployment::class)->create([
            'project_id' => $project->id,
            'number'     => 3,
            'user_id'    => $user->id,
        ]);
        $deployment4 = factory(Deployment::class)->create([
            'project_id' => $project->id,
            'number'     => 4,
            'user_id'    => $user->id,
        ]);

        $actual = $project->getDeploymentsWhereNumberBefore(3);

        $this->assertTrue($actual[0]->is($deployment2));
        $this->assertTrue($actual[1]->is($deployment1));
    }
}
