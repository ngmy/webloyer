<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Webhook\V1\GitHub\Deployment;

use Common\App\Service\ApplicationService;
use Illuminate\Http\Request;
use Webloyer\App\Service\Deployment\CreateDeploymentRequest;
use Webloyer\App\Service\Project\{
    GetProjectRequest,
    GetProjectService,
};

class DeployController extends BaseController
{
    private $projectService;

    public function __construct(
        ApplicationService $service,
        GetProjectService $projectService
    ) {
        parent::__construct($service);

        $this->projectService = $projectService;
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string  $projectId
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, string $projectId)
    {
        $projectServiceRequest = (new GetProjectRequest())->setId($projectId);
        $project = $this->projectService->execute($projectServiceRequest);

        $serviceRequest = (new CreateDeploymentRequest())
            ->setProjectId($project->id)
            ->setExecutor($project->gitHubWebhookUserId);
        $deployment = $this->service->execute($serviceRequest);

        return response()->json($deployment);
    }
}
