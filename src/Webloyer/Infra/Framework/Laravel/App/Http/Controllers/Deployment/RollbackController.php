<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment;

use Illuminate\Http\{
    RedirectResponse,
    Request,
};
use Webloyer\App\Service\Deployment\RollbackDeploymentRequest;
use Webloyer\Domain\Model\Project\ProjectDoesNotExistException;

class RollbackController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string  $projectId
     * @return RedirectResponse
     */
    public function __invoke(Request $request, string $projectId): RedirectResponse
    {
        $serviceRequest = (new RollbackDeploymentRequest())
            ->setProjectId($projectId)
            ->setExecutor($request->user()->toEntity()->id());

        assert(!is_null($this->service));

        try {
            $deployment = $this->service->execute($serviceRequest);
        } catch (ProjectDoesNotExistException $exception) {
            abort(404);
        }

        $link = link_to_route('projects.deployments.show', '#' . $deployment->number, [$projectId, $deployment->number]);
        $request->session()->flash('status', "The deployment $link was successfully started.");

        return redirect()->route('projects.deployments.index', [$projectId]);
    }
}
