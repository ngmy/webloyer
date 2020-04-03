<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Recipe\RecipeInterface;
use App\Services\Form\Recipe\RecipeForm;
use App\Models\Recipe;

class RecipesController extends Controller
{
    protected $recipe;

    protected $recipeForm;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Recipe\RecipeInterface $recipe
     * @param \App\Services\Form\Recipe\RecipeForm     $recipeForm
     * @return void
     */
    public function __construct(RecipeInterface $recipe, RecipeForm $recipeForm)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->recipe     = $recipe;
        $this->recipeForm = $recipeForm;
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

        $recipes = $this->recipe->byPage($page, $perPage);

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
     * @param \App\Models\Recipe $recipe
     * @return Response
     */
    public function show(Recipe $recipe)
    {
        $recipeProject = $recipe->getProjects()->toArray();

        return view('recipes.show')->with('recipe', $recipe)
            ->with('recipeProject', $recipeProject);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Recipe $recipe
     * @return Response
     */
    public function edit(Recipe $recipe)
    {
        return view('recipes.edit')->with('recipe', $recipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Recipe       $recipe
     * @return Response
     */
    public function update(Request $request, Recipe $recipe)
    {
        $input = array_merge($request->all(), ['id' => $recipe->id]);

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
     * @param \App\Models\Recipe $recipe
     * @return Response
     */
    public function destroy(Recipe $recipe)
    {
        $this->recipe->delete($recipe->id);

        return redirect()->route('recipes.index');
    }
}
