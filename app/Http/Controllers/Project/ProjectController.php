<?php

declare(strict_types=1);

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project as ProjectRequest;
use App\Repositories\Project\ProjectInterface;
use App\Repositories\Recipe\RecipeInterface;
use App\Repositories\Server\ServerInterface;
use App\Repositories\User\UserInterface;
use App\Services\Form\Project\ProjectForm;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /** @var ProjectInterface */
    private $project;
    /** @var ProjectForm */
    private $projectForm;
    /** @var RecipeInterface */
    private $recipe;
    /** @var ServerInterface */
    private $server;
    /** @var UserInterface */
    private $user;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Project\ProjectInterface $project
     * @param \App\Services\Form\Project\ProjectForm     $projectForm
     * @param \App\Repositories\Recipe\RecipeInterface   $recipe
     * @param \App\Repositories\Server\ServerInterface   $server
     * @param \App\Repositories\User\UserInterface       $user
     * @return void
     */
    public function __construct(
        ProjectInterface $project,
        ProjectForm $projectForm,
        RecipeInterface $recipe,
        ServerInterface $server,
        UserInterface $user
    ) {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->project     = $project;
        $this->projectForm = $projectForm;
        $this->recipe      = $recipe;
        $this->server      = $server;
        $this->user        = $user;
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
        $recipes = $this->recipe->all()->toArray();
        $recipes = array_column($recipes, 'name', 'id');

        $servers = $this->server->all()->toArray();
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

        $projectServer = $this->server->byId($project->server_id);

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
        $recipes = $this->recipe->all()->toArray();
        $recipes = array_column($recipes, 'name', 'id');

        $servers = $this->server->all()->toArray();
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
