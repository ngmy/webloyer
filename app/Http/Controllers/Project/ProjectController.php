<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project as ProjectRequest;
use Webloyer\App\Service\Project\{
    CreateProjectRequest,
    CreateProjectService,
    DeleteProjectRequest,
    DeleteProjectService,
    GetProjectRequest,
    GetProjectService,
    GetProjectsRequest,
    GetProjectsService,
    UpdateProjectRequest,
    UpdateProjectService,
};
use Webloyer\App\Service\Recipe\GetAllRecipesService;
use Webloyer\App\Service\Server\{
    GetAllServersService,
    GetServerRequest,
    GetServerService,
};
use Webloyer\App\Service\User\GetAllUsersService;
use Webloyer\Domain\Model\Project as ProjectDomainModel;

class ProjectController extends Controller
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
     * @param ProjectRequest\IndexRequest $request
     * @return Response
     */
    public function index(ProjectRequest\IndexRequest $request, GetProjectsService $service)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $serviceRequest = (new GetProjectsRequest())
            ->setPage($page)
            ->setPerPage($perPage);
        $projects = $service->execute($serviceRequest);

        return view('projects.index')->with('projects', $projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(GetAllRecipesService $recipeService, GetAllServersService $serverService, GetAllUsersService $userService)
    {
        $recipes = $recipeService->getAllRecipes()->toArray();
        $recipes = array_column($recipes, 'name', 'id');

        $servers = $serverService->getAllServers()->toArray();
        $servers = array_column($servers, 'name', 'id');

        $users = $userService->getAllUsers()->toArray();
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
    public function store(ProjectRequest\StoreRequest $request, CreateProjectService $service)
    {
        $input = $request->all();

        $serviceRequest = (new CreateProjectRequest())
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

        $service->execute($serviceRequest);

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param ProjectDomainModel\Project $project
     * @return Response
     */
    public function show(ProjectDomainModel\Project $project, GetServerService $serverService)
    {
        $projectRecipe = $project->getRecipes()->toArray();

        $serverServiceRequest = (new GetServerRequest())->setId($project->server_id);
        $projectServer = $serverService->getServer($serverServiceRequest);

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
    public function edit(ProjectDomainModel\Project $project, GetAllRecipesService $recipeService, GetAllServersService $serverService, GetAllUsersService $userService)
    {
        $recipes = $recipeService->getAllRecipes()->toArray();
        $recipes = array_column($recipes, 'name', 'id');

        $servers = $serverService->getAllServers()->toArray();
        $servers = array_column($servers, 'name', 'id');

        $projectRecipe = $project->getRecipes()->toArray();
        $projectRecipe = array_column($projectRecipe, 'id');

        $users = $userService->getAllUsers()->toArray();
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
    public function update(ProjectRequest\UpdateRequest $request, ProjectDomainModel\Project $project, UpdateProjectService $service)
    {
        $input = $request->all();

        $serviceRequest = (new UpdateProjectRequest())
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

        $service->execute($serviceRequest);

        return redirect()->route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ProjectDomainModel\Project $project
     * @return Response
     */
    public function destroy(ProjectDomainModel\Project $project, DeleteProjectService $service)
    {
        $serviceRequest = (new DeleteProjectRequest())->setId($project->id());

        $service->execute($serviceRequest);

        return redirect()->route('projects.index');
    }
}
