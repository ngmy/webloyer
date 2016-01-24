<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Deployment\DeploymentInterface;
use App\Services\Form\Deployment\DeploymentForm;
use App\Models\Project;
use App\Models\Deployment;

use Illuminate\Http\Request;

class DeploymentsController extends Controller
{
    protected $deployment;

    protected $deploymentForm;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Deployment\DeploymentInterface $deployment
     * @param \App\Services\Form\Deployment\DeploymentForm     $deploymentForm
     * @return void
     */
    public function __construct(DeploymentInterface $deployment, DeploymentForm $deploymentForm)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->deployment     = $deployment;
        $this->deploymentForm = $deploymentForm;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Project      $project
     * @return Response
     */
    public function index(Request $request, Project $project)
    {
        $page = $request->input('page', 1);

        $perPage = 10;

        $deployments = $this->deployment->byProjectId($project->id, $page, $perPage);

        return view('deployments.index')
            ->with('deployments', $deployments)
            ->with('project', $project);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Project      $project
     * @return Response
     */
    public function store(Request $request, Project $project)
    {
        $input = array_merge($request->all(), [
            'status'     => null,
            'message'    => null,
            'project_id' => $project->id,
            'user_id'    => $request->user()->id,
        ]);

        if ($this->deploymentForm->save($input)) {
            $deployment = $project->getLastDeployment();
            $link = link_to_route('projects.deployments.show', "#$deployment->number", [$project, $deployment->number]);
            $request->session()->flash('status', "The deployment $link was successfully started.");

            return redirect()->route('projects.deployments.index', [$project]);
        } else {
            return redirect()->route('projects.deployments.index', [$project])
                ->withInput()
                ->withErrors($this->deploymentForm->errors());
        }
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
