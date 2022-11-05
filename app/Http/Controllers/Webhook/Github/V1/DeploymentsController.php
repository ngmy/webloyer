<?php

namespace App\Http\Controllers\Webhook\Github\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Project\ProjectInterface;
use App\Services\Form\Deployment\DeploymentForm;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler;
use Throwable;

/**
 * Class DeploymentsController
 * @package App\Http\Controllers\Webhook\Github\V1
 */
class DeploymentsController extends Controller
{

    /**
     * @var ProjectInterface
     */
    protected ProjectInterface $project;

    /**
     * @var DeploymentForm
     */
    protected DeploymentForm $deploymentForm;

    protected Handler $loggerHandler;

    /**
     * DeploymentsController constructor.
     * @param ProjectInterface $project
     * @param DeploymentForm $deploymentForm
     * @param Handler $loggerHandler
     */
    public function __construct(ProjectInterface $project, DeploymentForm $deploymentForm, Handler $loggerHandler)
    {
        $this->project = $project;
        $this->deploymentForm = $deploymentForm;
        $this->loggerHandler = $loggerHandler;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return string
     * @throws Throwable
     */
    public function store(Request $request, Project $project)
    {
        $this->verify($request, $project);
        $requestContent = json_decode($request->getContent());

        if (empty($requestContent->ref)) {
            $error = new \Exception("Wrong bitbucket webhook request format!");
            $this->loggerHandler->report($error);
            return false;
        }

        if ($requestContent->ref === 'refs/heads/' . $project->stage) {
            $input = array_merge($request->all(), [
                'status' => null,
                'message' => null,
                'project_id' => $project->id,
                'user_id' => $project->github_webhook_user_id,
                'task' => 'deploy',
            ]);

            if ($this->deploymentForm->save($input)) {
                $deployment = $project->getLastDeployment();
                return $deployment->toJson();
            } else {
                abort(400, $this->deploymentForm->errors());
            }
        }
    }

    /**
     * @param $request
     * @param $project
     */
    private function verify($request, $project)
    {
        $secret = $project->github_webhook_secret;

        if (isset($secret)) {
            $signature = 'sha1=' . hash_hmac('sha1', $request->getContent(), $secret);

            if ($signature !== $request->header('X-Hub-Signature')) {
                abort(401);
            }
        }
    }
}
