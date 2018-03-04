<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentPresenter;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Application\Server\ServerService;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ProjectForm\ProjectForm;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

class ProjectsController extends Controller
{
    private $projectForm;

    private $projectService;

    private $deploymentService;

    private $recipeService;

    private $serverService;

    private $userService;

    /**
     * Create a new controller instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ProjectForm\ProjectForm $projectForm
     * @param \Ngmy\Webloyer\Webloyer\Application\Project\ProjectServicea       $projectService
     * @param \Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService  $deploymentService
     * @param \Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService          $recipeService
     * @param \Ngmy\Webloyer\Webloyer\Application\Server\ServerService          $serverService
     * @param \Ngmy\Webloyer\IdentityAccess\Application\User\UserService        $userService
     * @return void
     */
    public function __construct(ProjectForm $projectForm, ProjectService $projectService, DeploymentService $deploymentService, RecipeService $recipeService, ServerService $serverService, UserService $userService)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->projectForm = $projectForm;
        $this->projectService = $projectService;
        $this->deploymentService = $deploymentService;
        $this->recipeService = $recipeService;
        $this->serverService = $serverService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);

        $perPage = 10;

        $projects = $this->projectService->getProjectsByPage($page, $perPage);

        $lastDeployments = [];
        foreach ($projects as $project) {
            $lastDeployment = $this->deploymentService->getLastDeployment($project->projectId()->id());
            if (!is_null($lastDeployment)) {
                $lastDeployments[$project->projectId()->id()] = new DeploymentPresenter($lastDeployment, new AnsiToHtmlConverter());
            }
        }

        return view('projects.index')
            ->with('projects', $projects)
            ->with('lastDeployments', $lastDeployments);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $recipes = $this->recipeService->getAllRecipes();
        $recipeList = [];
        foreach ($recipes as $recipe) {
            $recipeList[$recipe->recipeId()->id()] = $recipe->name();
        }

        $servers = $this->serverService->getAllServers();
        $serverList = [];
        foreach ($servers as $server) {
            $serverList[$server->serverId()->id()] = $server->name();
        }

        $users = $this->userService->getAllUsers();
        $userList = [];
        foreach ($users as $user) {
            $userList[$user->userId()->id()] = $user->email();
        }
        $userList = ['' => ''] + $userList;

        return view('projects.create')
            ->with('recipes', $recipeList)
            ->with('servers', $serverList)
            ->with('users', $userList);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        if ($this->projectForm->save($input)) {
            return redirect()->route('projects.index');
        } else {
            return redirect()->route('projects.create')
                ->withInput()
                ->withErrors($this->projectForm->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project
     * @return Response
     */
    public function show(Project $project)
    {
        $projectRecipe = [];
        foreach ($project->recipeIds() as $recipeId) {
            $projectRecipe[] = $this->recipeService->getRecipeById($recipeId->id());
        }

        $projectServer = $this->serverService->getServerById($project->serverId()->id());

        return view('projects.show')
            ->with('project', $project)
            ->with('projectRecipe', $projectRecipe)
            ->with('projectServer', $projectServer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project
     * @return Response
     */
    public function edit(Project $project)
    {
        $recipes = $this->recipeService->getAllRecipes();
        $recipeList = [];
        foreach ($recipes as $recipe) {
            $recipeList[$recipe->recipeId()->id()] = $recipe->name();
        }

        $servers = $this->serverService->getAllServers();
        $serverList = [];
        foreach ($servers as $server) {
            $serverList[$server->serverId()->id()] = $server->name();
        }

        $projectRecipe = [];
        foreach ($project->recipeIds() as $recipeId) {
            $projectRecipe[] = $this->recipeService->getRecipeById($recipeId->id());
        }
        $projectRecipeList = [];
        foreach ($projectRecipe as $recipeId) {
            $projectRecipeList[] = $recipeId->recipeId()->id();
        }

        $users = $this->userService->getAllUsers();
        $userList = [];
        foreach ($users as $user) {
            $userList[$user->userId()->id()] = $user->email();
        }
        $userList = ['' => ''] + $userList;

        return view('projects.edit')
            ->with('project', $project)
            ->with('recipes', $recipeList)
            ->with('servers', $serverList)
            ->with('projectRecipe', $projectRecipeList)
            ->with('users', $userList);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project
     * @return Response
     */
    public function update(Request $request, Project $project)
    {
        $input = array_merge($request->all(), ['id' => $project->projectId()->id()]);

        if ($this->projectForm->update($input)) {
            return redirect()->route('projects.index');
        } else {
            return redirect()->route('projects.edit', [$project->projectId()->id()])
                ->withInput()
                ->withErrors($this->projectForm->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        $this->projectService->removeProject($project->projectId()->id());

        return redirect()->route('projects.index');
    }
}
