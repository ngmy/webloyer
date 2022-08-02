<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Repositories\Project\ProjectInterface;
use App\Repositories\Recipe\RecipeInterface;
use App\Repositories\Server\ServerInterface;
use App\Repositories\User\UserInterface;
use App\Services\Form\Project\ProjectForm;
use App\Models\Project;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class ProjectsController
 * @package App\Http\Controllers
 */
class ProjectsController extends Controller
{

    /**
     * @var ProjectInterface
     */
    protected ProjectInterface $project;

    /**
     * @var ProjectForm
     */
    protected ProjectForm $projectForm;

    /**
     * @var RecipeInterface
     */
    protected RecipeInterface $recipe;

    /**
     * @var ServerInterface
     */
    protected ServerInterface $server;

    /**
     * @var UserInterface
     */
    protected UserInterface $user;

    /**
     * @param ProjectInterface $project
     * @param ProjectForm $projectForm
     * @param RecipeInterface $recipe
     * @param ServerInterface $server
     * @param UserInterface $user
     * @return void
     */
    public function __construct(
        ProjectInterface $project,
        ProjectForm $projectForm,
        RecipeInterface $recipe,
        ServerInterface $server,
        UserInterface $user
    )
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->project = $project;
        $this->projectForm = $projectForm;
        $this->recipe = $recipe;
        $this->server = $server;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;
        $projects = $this->project->byPage($page, $perPage);
        return view('projects.index')->with('projects', $projects);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
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
     * @param Request $request
     * @return RedirectResponse
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
     * @param Project $project
     * @return Factory|View
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
     * @param Project $project
     * @return Factory|View
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
     * @param Request $request
     * @param Project $project
     * @return RedirectResponse
     */
    public function update(Request $request, Project $project)
    {
        $input = array_merge($request->all(), ['id' => $project->id]);
        if ($this->projectForm->update($input)) {
            return redirect()->route('projects.index');
        } else {
            return redirect()->route('projects.edit', [$project])
                ->withInput()
                ->withErrors($this->projectForm->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @return RedirectResponse
     */
    public function destroy(Project $project)
    {
        $this->project->delete($project->id);
        return redirect()->route('projects.index');
    }
}
