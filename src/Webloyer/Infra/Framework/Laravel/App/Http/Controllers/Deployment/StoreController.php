<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment;

use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Deployment\StoreRequest;
use Webloyer\App\Service\Deployment\{
    CreateDeploymentRequest,
    RollbackDeploymentRequest,
};

class StoreController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param StoreRequest $request
     * @param string       $projectId
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreRequest $request, string $projectId)
    {
        $serviceRequest = (
            $request->input('task') == 'deploy'
                ? new CreateDeploymentRequest()
                : new RollbackDeploymentRequest()
            )
            ->setProjectId($projectId)
            ->setExecutor($request->user()->id);
        $deployment = $this->service->execute($serviceRequest);

        $link = link_to_route('projects.deployments.show', '#' . $deployment->number(), [$project->id(), $deployment->number()]);
        $request->session()->flash('status', "The deployment $link was successfully started.");

        return redirect()->route('projects.deployments.index', [$projectId]);
    }
}
