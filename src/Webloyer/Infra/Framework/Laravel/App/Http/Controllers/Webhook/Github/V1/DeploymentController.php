<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Webhook\Github\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests;
use Webloyer\Infra\Framework\Laravel\App\Models\Project;
use Webloyer\Infra\Framework\Laravel\App\Services\Form\Deployment\DeploymentForm;

class DeploymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Project\ProjectInterface   $project
     * @param \App\Services\Form\Deployment\DeploymentForm $deploymentForm
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Project      $project
     * @return Response
     */
    public function store(Request $request, Project $project)
    {
        $input = array_merge($request->all(), [
            'status'     => null,
            'message'    => null,
            'project_id' => $project->id,
            'user_id'    => $project->github_webhook_user_id,
            'task'       => 'deploy',
        ]);

        if ($this->deploymentForm->save($input)) {
            $deployment = $project->getLastDeployment();
            return $deployment->toJson();
        } else {
            abort(400, $this->deploymentForm->errors());
        }
    }
}
