<?php
declare(strict_types=1);

namespace App\Services\Api;

use App\Repositories\Project\ProjectInterface;
use App\Services\Form\Deployment\DeploymentForm;
use App\Models\Deployment;
use Sajya\Server\Procedure;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

/**
 * Class JsonRpc
 * @package App\Services\Api
 */
class JsonRpc extends Procedure
{

    /**
     * @var string
     */
    public static string $name = 'jsonrpc';

    /**
     * @var ProjectInterface
     */
    protected ProjectInterface $project;

    /**
     * @var DeploymentForm
     */
    protected DeploymentForm $deploymentForm;

    /**
     * Create a new controller instance.
     *
     * @param ProjectInterface   $project
     * @param DeploymentForm $deploymentForm
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
     * @return Deployment
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
            return $project->getLastDeployment();
        } else {
            throw new InvalidArgumentException($this->deploymentForm->errors());
        }
    }

    /**
     * Roll back a deployment.
     *
     * @param int $project_id
     * @return Deployment
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
            return $project->getLastDeployment();
        } else {
            throw new InvalidArgumentException($this->deploymentForm->errors());
        }
    }

    /**
     * Unlock a deployment.
     *
     * @param int $project_id
     * @return Deployment
     */
    public function unlock($project_id)
    {
        $input = [
            'status'     => null,
            'message'    => null,
            'project_id' => $project_id,
            'user_id'    => Auth::guard('api')->user()->id,
            'task'       => 'unlock',
        ];

        if ($this->deploymentForm->save($input)) {
            $project = $this->project->byId($project_id);
            return $project->getLastDeployment();
        } else {
            throw new InvalidArgumentException($this->deploymentForm->errors());
        }
    }
}
