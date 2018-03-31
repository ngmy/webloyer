<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentPresenter;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentForm;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

class DeploymentsController extends Controller
{
    private $deploymentForm;

    private $deploymentService;

    private $userService;

    /**
     * Create a new controller instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentForm $deploymentForm
     * @param \Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService        $deploymentService
     * @param \Ngmy\Webloyer\IdentityAccess\Application\User\UserService              $userService
     * @return void
     */
    public function __construct(DeploymentForm $deploymentForm, DeploymentService $deploymentService, UserService $userService)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->deploymentForm = $deploymentForm;
        $this->deploymentService = $deploymentService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project
     * @return Response
     */
    public function index(Request $request, Project $project)
    {
        $page = $request->input('page', 1);

        $perPage = 10;

        $deployments = $this->deploymentService->getDeploymentsByPage(
            $project->projectId()->id(),
            $page,
            $perPage
        );

        $deployments->getCollection()->transform(function ($deployment, $key) {
            return new DeploymentPresenter($deployment, new AnsiToHtmlConverter());
        });

        $deployedUsers = [];
        foreach ($deployments as $deployment) {
            if (!is_null($deployment->deployedUserId()->id())) {
                $deployedUsers[$deployment->deploymentId()->id()] = $this->userService->getUserById($deployment->deployedUserId()->id());
            }
        }

        return view('deployments.index')
            ->with('deployments', $deployments)
            ->with('project', $project)
            ->with('deployedUsers', $deployedUsers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project
     * @return Response
     */
    public function store(Request $request, Project $project)
    {
        $input = array_merge($request->all(), [
            'status'     => null,
            'message'    => null,
            'project_id' => $project->projectId()->id(),
            'user_id'    => $request->user()->userId()->id(),
        ]);

        if ($this->deploymentForm->save($input)) {
            $lastDeployment = $this->deploymentService->getLastDeployment($project->projectId()->id());
            $link = link_to_route('projects.deployments.show', "#{$lastDeployment->deploymentId()->id()}", [$project->projectId()->id(), $lastDeployment->deploymentId()->id()]);
            $request->session()->flash('status', "The deployment $link was successfully started.");

            return redirect()->route('projects.deployments.index', [$project->projectId()->id()]);
        } else {
            return redirect()->route('projects.deployments.index', [$project->projectId()->id()])
                ->withInput()
                ->withErrors($this->deploymentForm->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project       $project
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment $deployment
     * @return Response
     */
    public function show(Project $project, Deployment $deployment)
    {
        if (!is_null($deployment->deployedUserId()->id())) {
            $deployedUser = $this->userService->getUserById($deployment->deployedUserId()->id());
        }

        $deployment = new DeploymentPresenter($deployment, new AnsiToHtmlConverter());

        return view('deployments.show')
            ->with('deployment', $deployment)
            ->with('deployedUser', $deployedUser);
    }
}
