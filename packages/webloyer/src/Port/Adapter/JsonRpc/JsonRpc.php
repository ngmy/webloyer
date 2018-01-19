<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc;

use Auth;
use InvalidArgumentException;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc\DeploymentResponse;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentForm;

class JsonRpc
{
    private $deploymentForm;

    private $deploymentService;

    /**
     * Create a new controller instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentForm $deploymentForm
     * @param \Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService        $deploymentService
     * @return void
     */
    public function __construct(DeploymentForm $deploymentForm, DeploymentService $deploymentService)
    {
        $this->deploymentForm = $deploymentForm;
        $this->deploymentService = $deploymentService;
    }

    /**
     * Deploy a project.
     *
     * @param int $project_id
     * @return string JSON
     */
    public function deploy($project_id)
    {
        $input = [
            'status'     => null,
            'message'    => null,
            'project_id' => $project_id,
            'user_id'    => Auth::guard('api')->user()->userId()->id(),
            'task'       => 'deploy',
        ];

        if ($this->deploymentForm->save($input)) {
            $lastDeployment = $this->deploymentService->getLastDeployment($project_id);
            return DeploymentResponse::fromDeployment($lastDeployment)->toJson();
        } else {
            throw new InvalidArgumentException($this->deploymentForm->errors());
        }
    }

    /**
     * Roll back a deployment.
     *
     * @param int $project_id
     * @return string JSON
     */
    public function rollback($project_id)
    {
        $input = [
            'status'     => null,
            'message'    => null,
            'project_id' => $project_id,
            'user_id'    => Auth::guard('api')->user()->userId()->id(),
            'task'       => 'rollback',
        ];

        if ($this->deploymentForm->save($input)) {
            $lastDeployment = $this->deploymentService->getLastDeployment($project_id);
            return DeploymentResponse::fromDeployment($lastDeployment)->toJson();
        } else {
            throw new InvalidArgumentException($this->deploymentForm->errors());
        }
    }
}
