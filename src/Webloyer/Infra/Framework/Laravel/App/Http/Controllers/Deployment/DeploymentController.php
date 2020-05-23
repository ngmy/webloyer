<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment;

use App\Http\Controllers\Controller;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Deployment as DeploymentRequest;
use Webloyer\App\Service\Deployment\{
    CreateDeploymentRequest,
    CreateDeploymentService,
    GetDeploymentService,
    GetDeploymentsRequest,
    GetDeploymentsService,
    RollbackDeploymentRequest,
    RollbackDeploymentService,
};
use Webloyer\Domain\Model\Deployment\Deployment;
use Webloyer\Domain\Model\Project\Project;

class DeploymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('acl');
    }

    /**
     * Display a listing of the resource.
     *
     * @param DeploymentRequest\IndexRequest $request
     * @param Project                        $project
     * @param GetDeploymentsService          $service
     * @return Response
     */
    public function index(DeploymentRequest\IndexRequest $request, Project $project, GetDeploymentsService $service)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $serviceRequest = (new GetDeploymentsRequest())
            ->setPage($page)
            ->setPerPage($perPage);
        $deployments = $service->execute($serviceRequest);

        return view('webloyer::deployments.index')
            ->with('deployments', $deployments)
            ->with('project', $project);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DeploymentRequest\StoreRequest $request
     * @param Project                        $project
     * @param CreateDeploymentService        $service
     * @return Response
     */
    public function store(DeploymenRequest\StoreRequest $request, Project $project, CreateDeploymentService $createService, RollbackDeploymentService $rollbackService)
    {
        if ($request->task == 'deploy') {
            $createServiceRequest = (new CreateDeploymentRequest())
                ->setProjectId($project->id())
                ->setExecutor($request->user()->id);
            $deployment = $createService->execute($createServiceRequest);
        } elseif ($request->task == 'rollback') {
            $rollbackServiceRequest = (new RollbackDeploymentRequest())
                ->setProjectId($project->id())
                ->setExecutor($request->user()->id);
            $deployment = $rollbackService->execute($rollbackServiceRequest);
        }

        $link = link_to_route('projects.deployments.show', '#' . $deployment->number(), [$project->id(), $deployment->number()]);
        $request->session()->flash('status', "The deployment $link was successfully started.");

        return redirect()->route('projects.deployments.index', [$project->id()]);
    }

    /**
     * Display the specified resource.
     *
     * @param Project              $project
     * @param Deployment           $deployment
     * @param GetDeploymentService $service
     * @return Response
     */
    public function show(Project $project, Deployment $deployment, GetDeploymentService $service)
    {
        return view('webloyer::deployments.show')->with('deployment', $deployment);
    }
}
