<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment;

use Illuminate\Http\{
    RedirectResponse,
    Request,
};
use Webloyer\App\Service\Deployment\CreateDeploymentRequest;
use Webloyer\Domain\Model\Project\ProjectDoesNotExistException;
use Webloyer\Domain\Model\Recipe\RecipeDoesNotExistException;
use Webloyer\Domain\Model\Server\ServerDoesNotExistException;
use Webloyer\Domain\Model\User\UserDoesNotExistException;

class DeployController extends BaseController
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
        $serviceRequest = (new CreateDeploymentRequest())
            ->setProjectId($projectId)
            ->setExecutor($request->user()->toEntity()->id());

        assert(!is_null($this->service));

        try {
            $deployment = $this->service->execute($serviceRequest);
        } catch (ProjectDoesNotExistException $exception) {
            abort(404);
        } catch (RecipeDoesNotExistException $exception) {
            return redirect()
                ->route('projects.deployments.index', [$projectId])
                ->withErrors(['The recipe does not exist. Check your project settings.']);
        } catch (ServerDoesNotExistException $exception) {
            return redirect()
                ->route('projects.deployments.index', [$projectId])
                ->withErrors(['The server does not exist. Check your project settings.']);
        } catch (UserDoesNotExistException $exception) {
            abort(500);
        }

        $link = link_to_route('projects.deployments.show', '#' . $deployment->number, [$projectId, $deployment->number]);
        $request->session()->flash('status', "The deployment $link was successfully started.");

        return redirect()->route('projects.deployments.index', [$projectId]);
    }
}
