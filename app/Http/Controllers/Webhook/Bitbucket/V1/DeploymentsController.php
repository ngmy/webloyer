<?php
declare(strict_types=1);

namespace App\Http\Controllers\Webhook\Bitbucket\V1;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Project\ProjectInterface;
use App\Services\Form\Deployment\DeploymentForm;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;

/**
 * Class DeploymentsController
 * @package App\Http\Controllers\Webhook\Bitbucket\V1
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

    /**
     * @var Handler
     */
    protected Handler $loggerHandler;

    /**
     * @var User
     */
    protected User $user;

    /**
     * DeploymentsController constructor.
     * @param ProjectInterface $project
     * @param DeploymentForm $deploymentForm
     * @param Handler $loggerHandler
     * @param User $user
     */
    public function __construct(ProjectInterface $project, DeploymentForm $deploymentForm, Handler $loggerHandler, User $user)
    {
        $this->project        = $project;
        $this->deploymentForm = $deploymentForm;
        $this->loggerHandler = $loggerHandler;
        $this->user = $user;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return string
     * @throws \Throwable
     */
    public function store(Request $request, Project $project)
    {
        $this->verify($request, $project);
        $requestContent = json_decode($request->getContent());
        if (!empty($requestContent->push->changes[0])) {
            $error = new \Exception("Wrong bitbucket webhook request format!");
            $this->loggerHandler->report($error);
        }
        if ($requestContent->push->changes[0]->new->name === $project->stage) {
            try {
                $user = $this->getBitbucketUserId($requestContent->actor->nickname);
            } catch (\Exception $e) {
                $user = false;
            }
            if (!$user) {
                $userId = $project->bitbucket_webhook_user_id;
            } else {
                $userId = $user->id;
            }
            try {
                $input = [
                    'status' => null,
                    'message' => null,
                    'project_id' => $project->id,
                    'user_id' => $userId,
                    'task' => 'deploy',
                    'actor' => 'bitbucket'
                ];
                if ($this->deploymentForm->save($input)) {
                    return response('OK', 200);
                } else {
                    abort(400, $this->deploymentForm->errors());
                }
            } catch (\Exception $e) {
                $this->loggerHandler->report($e);
            }
        }
    }

    /**
     * @param $request
     * @param $project
     */
    private function verify($request, $project) {
        $secretKey = $request->get('secret');
        $secret = $project->bitbucket_webhook_secret;

        if (isset($secret)) {
            $webhookKey = base64_decode($secretKey);
            if ($webhookKey !== $secret) {
                abort(401);
            }
        }
        return;
    }

    /**
     * @param $nickname
     * @return bool|mixed
     */
    private function getBitbucketUserId($nickname) {
        $bitbucketUser = $this->user->getByBitbucketNickname($nickname);
        if ($bitbucketUser) {
            return $bitbucketUser;
        }
        return false;
    }
}
