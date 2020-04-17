<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project as ProjectRequest;
use App\Repositories\Project\ProjectInterface;
use App\Repositories\User\UserInterface;
use App\Services\Form\Project\ProjectForm;
use App\Models\Project;
use Webloyer\App\Recipe;
use Webloyer\App\Server;

class ProjectController extends Controller
{
    /** @var ProjectInterface */
    private $project;
    /** @var ProjectForm */
    private $projectForm;
    /** @var Recipe\RecipeService */
    private $recipeService;
    /** @var Server\ServerService */
    private $serverService;
    /** @var UserInterface */
    private $user;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Project\ProjectInterface $project
     * @param \App\Services\Form\Project\ProjectForm     $projectForm
     * @param Recipe\RecipeService                       $recipeService
     * @param Server\ServerService                       $serverService
     * @param \App\Repositories\User\UserInterface       $user
     * @return void
     */
    public function __construct(
        ProjectInterface $project,
        ProjectForm $projectForm,
        Recipe\RecipeService $recipeService,
        Server\ServerService $serverService,
        UserInterface $user
    ) {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->project       = $project;
        $this->projectForm   = $projectForm;
        $this->recipeService = $recipeService;
        $this->serverService = $serverService;
        $this->user          = $user;
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

        $projects = $this->project->byPage($page, $perPage);

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

        $users = $this->user->all()->toArray();
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

        $this->projectForm->save($input);

        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Project $project
     * @return Response
     */
    public function show(Project $project)
    {
        $projectRecipe = $project->getRecipes()->toArray();

        $serverCommand = (new Server\Commands\GetServerCommand())->setId($project->server_id);
        $projectServer = $this->serverService->getServer($serverCommand);

        return view('projects.show')
            ->with('project', $project)
            ->with('projectRecipe', $projectRecipe)
            ->with('projectServer', $projectServer);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Project $project
     * @return Response
     */
    public function edit(Project $project)
    {
        $recipes = $this->recipeService->getAllRecipes()->toArray();
        $recipes = array_column($recipes, 'name', 'id');

        $servers = $this->serverService->getAllServers()->toArray();
        $servers = array_column($servers, 'name', 'id');

        $projectRecipe = $project->getRecipes()->toArray();
        $projectRecipe = array_column($projectRecipe, 'id');

        $users = $this->user->all()->toArray();
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
     * @param \App\Models\Project          $project
     * @return Response
     */
    public function update(ProjectRequest\UpdateRequest $request, Project $project)
    {
        $input = array_merge($request->all(), ['id' => $project->id]);

        $this->projectForm->update($input);

        return redirect()->route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Project $project
     * @return Response
     */
    public function destroy(Project $project)
    {
        $this->project->delete($project->id);

        return redirect()->route('projects.index');
    }
}
