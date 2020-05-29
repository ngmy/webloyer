<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Project as ProjectRequest;
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

        return view('webloyer::projects.show')
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

        return view('webloyer::projects.edit')
            ->with('project', $project)
            ->with('recipes', $recipes)
            ->with('servers', $servers)
            ->with('projectRecipe', $projectRecipe)
            ->with('users', $users);
    }
}
