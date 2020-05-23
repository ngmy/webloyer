<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use App\Http\Controllers\Controller;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Recipe as RecipeRequest;
use Webloyer\App\Service\Recipe\{
    CreateRecipeRequest,
    CreateRecipeService,
    DeleteRecipeRequest,
    DeleteRecipeService,
    GetRecipeRequest,
    GetRecipeService,
    GetRecipesRequest,
    GetRecipesService,
    UpdateRecipeRequest,
    UpdateRecipeService,
};
use Webloyer\Domain\Model\Recipe as RecipeDomainModel;

class RecipeController extends Controller
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
     * @param RecipeeRequest\IndexRequest $request
     * @return Response
     */
    public function index(RecipeRequest\IndexRequest $request, GetRecipesServie $service)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $serviceRequest = (new GetRecipesRequest())
            ->setPage($page)
            ->setPerPage($perPage);
        $recipes = $service->getRecipes($serviceRequest);

        return view('webloyer::recipes.index')->with('recipes', $recipes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('webloyer::recipes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RecipeRequest\StoreRequest $request
     * @return Response
     */
    public function store(RecipeRequest\StoreRequest $request, CreateRecipeService $service)
    {
        $input = $request->all();

        $serviceRequest = (new CreateRecipeRequest())
            ->setName($input['name'])
            ->setDescription($input['description'])
            ->setBody($input['body']);
        $service->execute($serviceRequest);

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

        return view('webloyer::recipes.show')->with('recipe', $recipe)
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
        return view('webloyer::recipes.edit')->with('recipe', $recipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RecipeRequest\UpdateRequest $request
     * @param RecipeDomainModel\Recipe    $recipe
     * @return Response
     */
    public function update(RecipeRequest\UpdateRequest $request, RecipeDomainModel\Recipe $recipe, UpdateRecipeService $service)
    {
        $input = $request->all();

        $serviceRequest = (new UpdateRecipeRequest())
            ->setId($recipe->id())
            ->setName($input['name'])
            ->setDescription($input['description'])
            ->setBody($input['body']);
        $service->execute($serviceRequest);

        return redirect()->route('recipes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RecipeDomainModel\Recipe $recipe
     * @return Response
     */
    public function destroy(RecipeDomainModel\Recipe $recipe, DeleteRecipeService $service)
    {
        $serviceRequest = (new DeleteRecipeRequest())->setId($recipe->id());
        $service->execute($serviceRequest);

        return redirect()->route('recipes.index');
    }
}
