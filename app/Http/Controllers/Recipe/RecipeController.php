<?php

declare(strict_types=1);

namespace App\Http\Controllers\Recipe;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recipe as RecipeRequest;
use Webloyer\App\Recipe as RecipeApplication;
use Webloyer\Domain\Model\Recipe as RecipeDomainModel;

class RecipeController extends Controller
{
    /** @var RecipeApplication\RecipeService */
    private $recipeService;

    /**
     * Create a new controller instance.
     *
     * @param RecipeApplication\RecipeService $recipeService
     * @return void
     */
    public function __construct(RecipeApplication\RecipeService $recipeService)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->recipeService = $recipeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param RecipeeRequest\IndexRequest $request
     * @return Response
     */
    public function index(RecipeRequest\IndexRequest $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $command = (new RecipeApplication\Commands\GetRecipesCommand())
            ->setPage($page)
            ->setPerPage($perPage);

        $recipes = $this->recipeService->getRecipes($command);

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
     * @param RecipeRequest\StoreRequest $request
     * @return Response
     */
    public function store(RecipeRequest\StoreRequest $request)
    {
        $input = $request->all();

        $command = (new RecipeApplication\Commands\CreateRecipeCommand())
            ->setName($input['name'])
            ->setDescription($input['description'])
            ->setBody($input['body']);

        $this->recipeService->createRecipe($command);

        return redirect()->route('recipes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param RecipeDomainModel\Recipe $recipe
     * @return Response
     */
    public function show(RecipeDomainModel\Recipe $recipe)
    {
        $recipeProject = $recipe->getProjects()->toArray();

        return view('recipes.show')->with('recipe', $recipe)
            ->with('recipeProject', $recipeProject);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param RecipeDomainModel\Recipe $recipe
     * @return Response
     */
    public function edit(RecipeDomainModel\Recipe $recipe)
    {
        return view('recipes.edit')->with('recipe', $recipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RecipeRequest\UpdateRequest $request
     * @param RecipeDomainModel\Recipe    $recipe
     * @return Response
     */
    public function update(RecipeRequest\UpdateRequest $request, RecipeDomainModel\Recipe $recipe)
    {
        $input = $request->all();

        $command = (new RecipeApplication\Commands\UpdateRecipeCommand())
            ->setId($recipe->id())
            ->setName($input['name'])
            ->setDescription($input['description'])
            ->setBody($input['body']);

        $this->recipeService->updateRecipe($command);

        return redirect()->route('recipes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RecipeDomainModel\Recipe $recipe
     * @return Response
     */
    public function destroy(RecipeDomainModel\Recipe $recipe)
    {
        $command = (new RecipeApplication\Commands\DeleteRecipeCommand())->setId($recipe->id());

        $this->recipeService->deleteRecipe($command);

        return redirect()->route('recipes.index');
    }
}
