<?php

declare(strict_types=1);

namespace App\Http\Controllers\Deployment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deployment as DeploymentRequest;
use App\Models\Deployment;
use App\Models\Project;
use App\Repositories\Project\ProjectInterface;
use App\Services\Form\Deployment\DeploymentForm;

class DeploymentController extends Controller
{
    /** @var ProjectInterface */
    private $project;
    /** @var DeploymentForm */
    private $deploymentForm;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Project\ProjectInterface   $project
     * @param \App\Services\Form\Deployment\DeploymentForm $deploymentForm
     * @return void
     */
    public function __construct(ProjectInterface $project, DeploymentForm $deploymentForm)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->project        = $project;
        $this->deploymentForm = $deploymentForm;
    }

    /**
     * Display a listing of the resource.
     *
     * @param DeploymentRequest\IndexRequest $request
     * @param \App\Models\Project            $project
     * @return Response
     */
    public function index(DeploymentRequest\IndexRequest $request, Project $project)
    {
        $page = $request->input('page', 1);

        $perPage = 10;

        $deployments = $project->getDeploymentsByPage($page, $perPage);

        return view('deployments.index')
            ->with('deployments', $deployments)
            ->with('project', $project);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DeploymentRequest\StoreRequest $request
     * @param \App\Models\Project            $project
     * @return Response
     */
    public function store(DeploymenRequest\StoreRequest $request, Project $project)
    {
        $input = array_merge($request->all(), [
            'status'     => null,
            'message'    => null,
            'project_id' => $project->id,
            'user_id'    => $request->user()->id,
        ]);

        $this->deploymentForm->save($input);

        $deployment = $project->getLastDeployment();
        $link = link_to_route('projects.deployments.show', "#$deployment->number", [$project, $deployment->number]);
        $request->session()->flash('status', "The deployment $link was successfully started.");

        return redirect()->route('projects.deployments.index', [$project]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project    $project
     * @param \App\Models\Deployment $deployment
     * @return Response
     */
    public function show(Project $project, Deployment $deployment)
    {
        return view('deployments.show')->with('deployment', $deployment);
    }
}
