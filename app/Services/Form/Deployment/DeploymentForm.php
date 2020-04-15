<?php

namespace App\Services\Form\Deployment;

use App\Services\Deployment\DeployCommanderInterface;
use App\Repositories\Project\ProjectInterface;
use DB;

class DeploymentForm
{
    protected $project;

    protected $deployCommander;

    /**
     * Create a new form service instance.
     *
     * @param \App\Repositories\Project\ProjectInterface        $project
     * @param \App\Services\Deployment\DeployCommanderInterface $deployCommander
     * @return void
     */
    public function __construct(
        ProjectInterface $project,
        DeployCommanderInterface $deployCommander
    ) {
        $this->project         = $project;
        $this->deployCommander = $deployCommander;
    }

    /**
     * Create a new deployment.
     *
     * @param array $input Data to create a deployment
     * @return boolean
     */
    public function save(array $input)
    {
        $deployment = DB::transaction(function () use ($input) {
            $project = $this->project->byId($input['project_id']);

            $maxDeployment = $project->getMaxDeployment();
            $input['number'] = $maxDeployment->number + 1;

            $project->addDeployment($input);
            $project->updateMaxDeployment(['number' => $input['number']]);

            $deployment = $project->getDeploymentByNumber($input['number']);

            return $deployment;
        });

        if (!$deployment) {
            return false;
        }

        $this->deployCommander->{$input['task']}($deployment);

        return true;
    }
}
