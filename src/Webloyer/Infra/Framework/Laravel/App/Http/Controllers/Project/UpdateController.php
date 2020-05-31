<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use Webloyer\App\Service\Project\UpdateProjectRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Project\UpdateRequest;

class UpdateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateRequest $request
     * @param string        $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateRequest $request, string $id)
    {
        $serviceRequest = (new UpdateProjectRequest())
            ->setId($id)
            ->setName($request->input('name'))
            ->setRecipeIds(...$request->input('recipe_id'))
            ->setServerId($request->input('server_id'))
            ->setRepositoryUrl($request->input('repository'))
            ->setStageName($request->input('stage'))
            ->setDeployPath($request->input('deploy_path'))
            ->setEmailNotificationRecipient($request->input('email_notification_recipient'))
            ->setDeploymentKeepDays($request->input('days_to_keep_deployments'))
            ->setKeepLastDeployment($request->input('keep_last_deployment'))
            ->setDeploymentKeepMaxNumber($request->input('max_number_of_deployments_to_keep'))
            ->setGithubWebhookSecret($request->input('github_webhook_secret'))
            ->setGithubWebhookExecutor($request->input('github_webhook_user_id'));
        $this->service->execute($serviceRequest);

        return redirect()->route('projects.index');
    }
}