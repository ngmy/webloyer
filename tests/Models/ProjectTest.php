<?php

use App\Models\Project;

use Tests\Helpers\Factory;
use Carbon\Carbon;

class ProjectTest extends TestCase
{
    protected $useDatabase = true;

    public function test_Should_GetDeploymentsWhereCreatedAtBefore()
    {
        $arrangedServer = Factory::create('App\Models\Server');

        $arrangedProject = Factory::create('App\Models\Project', [
            'server_id' => $arrangedServer->id,
        ]);

        $arrangedDeployments1 = Factory::create('App\Models\Deployment', [
            'project_id' => $arrangedProject->id,
            'number'     => 1,
            'created_at' => Carbon::create(2016, 8, 16, 0, 0, 0),
        ]);
        $arrangedDeployments2 = Factory::create('App\Models\Deployment', [
            'project_id' => $arrangedProject->id,
            'number'     => 2,
            'created_at' => Carbon::create(2016, 8, 16, 23, 59, 59),
        ]);
        $arrangedDeployments3 = Factory::create('App\Models\Deployment', [
            'project_id' => $arrangedProject->id,
            'number'     => 3,
            'created_at' => Carbon::create(2016, 8, 17, 0, 0, 0),
        ]);
        $arrangedDeployments4 = Factory::create('App\Models\Deployment', [
            'project_id' => $arrangedProject->id,
            'number'     => 4,
            'created_at' => Carbon::create(2016, 8, 17, 23, 59, 59),
        ]);

        $foundDeployments = $arrangedProject->getDeploymentsWhereCreatedAtBefore(Carbon::create(2016, 8, 17, 0, 0, 0));

        $this->assertEquals($arrangedDeployments2, $foundDeployments[0]);
        $this->assertEquals($arrangedDeployments1, $foundDeployments[1]);
    }

    public function test_Should_GetDeploymentsWhereNumberBefore()
    {
        $arrangedServer = Factory::create('App\Models\Server');

        $arrangedProject = Factory::create('App\Models\Project', [
            'server_id' => $arrangedServer->id,
        ]);

        $arrangedDeployments1 = Factory::create('App\Models\Deployment', [
            'project_id' => $arrangedProject->id,
            'number'     => 1,
        ]);
        $arrangedDeployments2 = Factory::create('App\Models\Deployment', [
            'project_id' => $arrangedProject->id,
            'number'     => 2,
        ]);
        $arrangedDeployments3 = Factory::create('App\Models\Deployment', [
            'project_id' => $arrangedProject->id,
            'number'     => 3,
        ]);
        $arrangedDeployments4 = Factory::create('App\Models\Deployment', [
            'project_id' => $arrangedProject->id,
            'number'     => 4,
        ]);

        $foundDeployments = $arrangedProject->getDeploymentsWhereNumberBefore(3);

        $this->assertEquals($arrangedDeployments2, $foundDeployments[0]);
        $this->assertEquals($arrangedDeployments1, $foundDeployments[1]);
    }
}
