<?php

namespace App\Services\Api;

use App\Repositories\Project\ProjectInterface;
use App\Services\Form\Deployment\DeploymentForm;
use Auth;
use InvalidArgumentException;

class JsonRpc
{
    protected $project;

    protected $deploymentForm;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Project\ProjectInterface   $project
     * @param \App\Services\Form\Deployment\DeploymentForm $deploymentForm
     * @return void
     */
    public function __construct(ProjectInterface $project, DeploymentForm $deploymentForm)
    {
        $this->project        = $project;
        $this->deploymentForm = $deploymentForm;
    }

    /**
     * Deploy a project.
     *
     * @param int $project_id
     * @return \App\Models\Deployment
     */
    public function deploy($project_id)
    {
        $input = [
            'status'     => null,
            'message'    => null,
            'project_id' => $project_id,
            'user_id'    => Auth::guard('api')->user()->id,
            'task'       => 'deploy',
        ];

        if ($this->deploymentForm->save($input)) {
            $project = $this->project->byId($project_id);
            $deployment = $project->getLastDeployment();
            return $deployment;
        } else {
            throw new InvalidArgumentException($this->deploymentForm->errors());
        }
    }

    /**
     * Roll back a deployment.
     *
     * @param int $project_id
     * @return \App\Models\Deployment
     */
    public function rollback($project_id)
    {
        $input = [
            'status'     => null,
            'message'    => null,
            'project_id' => $project_id,
            'user_id'    => Auth::guard('api')->user()->id,
            'task'       => 'rollback',
        ];

        if ($this->deploymentForm->save($input)) {
            $project = $this->project->byId($project_id);
            $deployment = $project->getLastDeployment();
            return $deployment;
        } else {
            throw new InvalidArgumentException($this->deploymentForm->errors());
        }
    }
}
