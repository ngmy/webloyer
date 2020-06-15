<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Webhook\V1\GitHub\Deployment;

use Common\App\Service\ApplicationService;
use Illuminate\Http\{
    JsonResponse,
    Request,
};
use Webloyer\App\Service\Deployment\RollbackDeploymentRequest;
use Webloyer\App\Service\Project\{
    GetProjectRequest,
    GetProjectService,
};

class RollbackController extends BaseController
{
    /** @var GetProjectService */
    private $projectService;

    /**
     * @param ApplicationService $service
     * @param GetProjectService  $projectService
     * @return void
     */
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
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $projectId): JsonResponse
    {
        $projectServiceRequest = (new GetProjectRequest())->setId($projectId);
        $project = $this->projectService->execute($projectServiceRequest);

        $serviceRequest = (new RollbackDeploymentRequest())
            ->setProjectId($project->id)
            ->setExecutor($project->gitHubWebhookUserId);
        assert(!is_null($this->service));
        $deployment = $this->service->execute($serviceRequest);

        return response()->json($deployment);
    }
}
