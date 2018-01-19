<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm\RecipeForm;

class RecipesController extends Controller
{
    private $recipeForm;

    private $recipeService;

    /**
     * Create a new controller instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm\RecipeForm $recipeForm
     * @param \Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService        $recipeService
     * @param \Ngmy\Webloyer\Webloyer\Application\Project\ProjectService      $projectService
     * @return void
     */
    public function __construct(RecipeForm $recipeForm, RecipeService $recipeService, ProjectService $projectService)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->recipeForm = $recipeForm;
        $this->recipeService = $recipeService;
        $this->projectService = $projectService;
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

        $recipes = $this->recipeService->getRecipesOfPage($page, $perPage);

        return view('recipes.index')->with('recipes', $recipes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('recipes.create');
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

        if ($this->recipeForm->save($input)) {
            return redirect()->route('recipes.index');
        } else {
            return redirect()->route('recipes.create')
                ->withInput()
                ->withErrors($this->recipeForm->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe $recipe
     * @return Response
     */
    public function show(Recipe $recipe)
    {
        $afferentProjectIds = $recipe->afferentProjectIds();
        $afferentProjects = [];
        foreach ($afferentProjectIds as $afferentProjectId) {
            $afferentProjects[] = $this->projectService->getProjectById($afferentProjectId->id());
        }

        return view('recipes.show')->with('recipe', $recipe)
            ->with('afferentProjects', $afferentProjects);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe $recipe
     * @return Response
     */
    public function edit(Recipe $recipe)
    {
        return view('recipes.edit')->with('recipe', $recipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request                           $request
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe $recipe
     * @return Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        $input = array_merge($request->all(), ['id' => $recipe->recipeId()->id()]);

        if ($this->recipeForm->update($input)) {
            return redirect()->route('recipes.index');
        } else {
            return redirect()->route('recipes.edit', [$recipe])
                ->withInput()
                ->withErrors($this->recipeForm->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe $recipe
     * @return Response
     */
    public function destroy(Recipe $recipe)
    {
        $this->recipeService->removeRecipe($recipe->recipeId()->id());

        return redirect()->route('recipes.index');
    }
}
