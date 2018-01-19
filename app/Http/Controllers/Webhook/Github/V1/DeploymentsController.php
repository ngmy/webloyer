<?php

namespace App\Http\Controllers\Webhook\Github\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc\DeploymentResponse;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentForm;

class DeploymentsController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project
     * @return Response
     */
    public function store(Request $request, Project $project)
    {
        $input = array_merge($request->all(), [
            'status'     => null,
            'message'    => null,
            'project_id' => $project->projectId()->id(),
            'user_id'    => $project->githubWebhookExecuteUserId()->id(),
            'task'       => 'deploy',
        ]);

        if ($this->deploymentForm->save($input)) {
            $lastDeployment = $this->deploymentService->getLastDeployment($project->projectId()->id());
            return DeploymentResponse::fromDeployment($lastDeployment)->toJson();
        } else {
            abort(400, $this->deploymentForm->errors());
        }
    }
}
