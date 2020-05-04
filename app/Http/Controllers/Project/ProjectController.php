<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project as ProjectRequest;
use Webloyer\App\Project as ProjectApplication;
use Webloyer\App\Recipe as RecipeApplication;
use Webloyer\App\Server as ServerApplication;
use Webloyer\App\User as UserApplication;
use Webloyer\Domain\Model\Project as ProjectDomainModel;

class ProjectController extends Controller
{
    /** @var ProjectApplication\ProjectService */
    private $projectService;
    /** @var RecipeApplication\RecipeService */
    private $recipeService;
    /** @var ServerApplication\ServerService */
    private $serverService;
    /** @var UserApplication\UserService */
    private $userService;

    /**
     * Create a new controller instance.
     *
     * @param ProjectApplication\ProjectService $projectService
     * @param RecipeApplication\RecipeService   $recipeService
     * @param ServerApplication\ServerService   $serverService
     * @param UserApplication\UserService       $userService
     * @return void
     */
    public function __construct(
        ProjectApplication\ProjectService $projectService,
        RecipeApplication\RecipeService $recipeService,
        ServerApplication\ServerService $serverService,
        UserApplication\UserService $userService
    ) {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->projectService = $projectService;
        $this->recipeService  = $recipeService;
        $this->serverService  = $serverService;
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ProjectRequest\IndexRequest $request
     * @return Response
     */
    public function index(ProjectRequest\IndexRequest $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $command = (new ProjectApplication\Commands\GetProjectsCommand())
            ->setPage($page)
            ->setPerPage($perPage);

        $projects = $this->projectService->getProjects($command);

        return view('projects.index')->with('projects', $projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $recipes = $this->recipeService->getAllRecipes()->toArray();
        $recipes = array_column($recipes, 'name', 'id');

        $servers = $this->serverService->getAllServers()->toArray();
        $servers = array_column($servers, 'name', 'id');

        $users = $this->userService->getAllUsers()->toArray();
        $users = array_column($users, 'email', 'id');
        $users = ['' => ''] + $users;

        return view('projects.create')
            ->with('recipes', $recipes)
            ->with('servers', $servers)
            ->with('users', $users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectRequest\StoreRequest $request
     * @return Response
     */
    public function store(ProjectRequest\StoreRequest $request)
    {
        $input = $request->all();

        $command = (new ProjectApplication\Commands\CreateProjectCommand())
            ->setName($input['name'])
            ->setRecipeIds($input['stage'])
            ->setServerId($input['recipe_id'])
            ->setRepositoryUrl($input['repository'])
            ->setStageName($input['stage'])
            ->setDeployPath($input['deploy_path'])
            ->setEmailNotificationRecipient($input['email_notification_recipient'])
            ->setDeploymentKeepDays($input['days_to_keep_deployments'])
            ->setKeepLastDeployment($input['keep_last_deployment'])
            ->setDeploymentKeepMaxNumber($input['max_number_of_deployments_to_keep'])
            ->setGithubWebhookSecret($input['github_webhook_secret'])
            ->setGithubWebhookExecutor($input['']);

        $this->projectService->createProject($command);

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param ProjectDomainModel\Project $project
     * @return Response
     */
    public function show(ProjectDomainModel\Project $project)
    {
        $projectRecipe = $project->getRecipes()->toArray();

        $serverCommand = (new ServerApplication\Commands\GetServerCommand())->setId($project->server_id);
        $projectServer = $this->serverService->getServer($serverCommand);

        return view('projects.show')
            ->with('project', $project)
            ->with('projectRecipe', $projectRecipe)
            ->with('projectServer', $projectServer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ProjectDomainModel\Project $project
     * @return Response
     */
    public function edit(ProjectDomainModel\Project $project)
    {
        $recipes = $this->recipeService->getAllRecipes()->toArray();
        $recipes = array_column($recipes, 'name', 'id');

        $servers = $this->serverService->getAllServers()->toArray();
        $servers = array_column($servers, 'name', 'id');

        $projectRecipe = $project->getRecipes()->toArray();
        $projectRecipe = array_column($projectRecipe, 'id');

        $users = $this->userService->getAllUsers()->toArray();
        $users = array_column($users, 'email', 'id');
        $users = ['' => ''] + $users;

        return view('projects.edit')
            ->with('project', $project)
            ->with('recipes', $recipes)
            ->with('servers', $servers)
            ->with('projectRecipe', $projectRecipe)
            ->with('users', $users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProjectRequest\UpdateRequest $request
     * @param ProjectDomainModel\Project   $project
     * @return Response
     */
    public function update(ProjectRequest\UpdateRequest $request, ProjectDomainModel\Project $project)
    {
        $input = $request->all();

        $command = (new ProjectApplication\Commands\UpdateProjectCommand())
            ->setId($project->id())
            ->setName($input['name'])
            ->setRecipeIds($input['stage'])
            ->setServerId($input['recipe_id'])
            ->setRepositoryUrl($input['repository'])
            ->setStageName($input['stage'])
            ->setDeployPath($input['deploy_path'])
            ->setEmailNotificationRecipient($input['email_notification_recipient'])
            ->setDeploymentKeepDays($input['days_to_keep_deployments'])
            ->setKeepLastDeployment($input['keep_last_deployment'])
            ->setDeploymentKeepMaxNumber($input['max_number_of_deployments_to_keep'])
            ->setGithubWebhookSecret($input['github_webhook_secret'])
            ->setGithubWebhookExecutor($input['']);

        $this->projectService->updateProject($command);

        return redirect()->route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProjectDomainModel\Project $project
     * @return Response
     */
    public function destroy(ProjectDomainModel\Project $project)
    {
        $command = (new ProjectApplication\Commands\DeleteProjectCommand())->setId($project->id());

        $this->projectService->deleteProject($command);

        return redirect()->route('projects.index');
    }
}
