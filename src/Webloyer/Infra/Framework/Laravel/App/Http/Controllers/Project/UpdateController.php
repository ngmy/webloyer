<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use Illuminate\Http\RedirectResponse;
use Webloyer\App\Service\Project\UpdateProjectRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Project\UpdateRequest;

class UpdateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateRequest $request
     * @param string        $id
     * @return RedirectResponse
     */
    public function __invoke(UpdateRequest $request, string $id): RedirectResponse
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
            ->setGitHubWebhookSecret($request->input('github_webhook_secret'))
            ->setGitHubWebhookExecutor($request->input('github_webhook_user_id'));
        assert(!is_null($this->service));
        $this->service->execute($serviceRequest);

        return redirect()->route('projects.index');
    }
}
