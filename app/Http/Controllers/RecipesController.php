<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\Recipe\RecipeInterface;
use App\Services\Form\Recipe\RecipeForm;
use App\Models\Recipe;

/**
 * Class RecipesController
 * @package App\Http\Controllers
 */
class RecipesController extends Controller
{

    /**
     * @var RecipeInterface
     */
    protected RecipeInterface $recipe;

    /**
     * @var RecipeForm
     */
    protected RecipeForm $recipeForm;

    /**
     * RecipesController constructor.
     * @param RecipeInterface $recipe
     * @param RecipeForm $recipeForm
     */
    public function __construct(RecipeInterface $recipe, RecipeForm $recipeForm)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->recipe = $recipe;
        $this->recipeForm = $recipeForm;
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
        $recipes = $this->recipe->byPage($page, $perPage);
        return view('recipes.index')->with('recipes', $recipes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('recipes.create');
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
     * @param Recipe $recipe
     * @return Factory|View
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
     * @param Recipe $recipe
     * @return Factory|View
     */
    public function edit(Recipe $recipe)
    {
        return view('recipes.edit')->with('recipe', $recipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Recipe $recipe
     * @return RedirectResponse
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
     * @param Recipe $recipe
     * @return RedirectResponse
     */
    public function destroy(Recipe $recipe)
    {
        $this->recipe->delete($recipe->id);

        return redirect()->route('recipes.index');
    }
}
