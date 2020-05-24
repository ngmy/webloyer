<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use App\Http\Controllers\Controller;
use Webloyer\App\Service\Recipe\{
    CreateRecipeRequest,
    DeleteRecipeRequest,
    GetRecipeRequest,
    GetRecipeService,
    GetRecipesService,
    UpdateRecipeRequest,
};
use Webloyer\Domain\Model\Recipe\Recipe;;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Recipe\{
    IndexRequest,
    StoreRequest,
    UpdateRequest,
};

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
     * @param IndexRequest      $request
     * @param GetRecipesService $service
     * @return Response
     */
    public function index(IndexRequest $request, GetRecipesService $service)
    {
        $service->recipesDataTransformer()->setPerPage(10);
        $recipes = $service->execute();

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
     * @param StoreRequest                    $request
     * @param TransactionalApplicationService $service
     * @return Response
     */
    public function store(StoreRequest $request, TransactionalApplicationService $service)
    {
        $serviceRequest = (new CreateRecipeRequest())
            ->setName($request->input('name'))
            ->setDescription($request->input('description'))
            ->setBody($request->input('body'));
        $service->execute($serviceRequest);

        return redirect()->route('recipes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param string           $id
     * @param GetRecipeService $service
     * @return Response
     */
    public function show(string $id, GetRecipeService $service)
    {
        //$recipeProject = $recipe->getProjects()->toArray();
        $recipeProject = [];
        $serviceRequest = (new GetRecipeRequest())->setId($id);
        $recipe = $service->execute($serviceRequest);

        return view('webloyer::recipes.show')
            ->with('recipe', $recipe)
            ->with('recipeProject', $recipeProject);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string           $id
     * @param GetRecipeService $service
     * @return Response
     */
    public function edit(string $id, GetRecipeService $service)
    {
        $serviceRequest = (new GetRecipeRequest())->setId($id);
        $recipe = $service->execute($serviceRequest);

        return view('webloyer::recipes.edit')->with('recipe', $recipe);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest                   $request
     * @param string                          $id
     * @param TransactionalApplicationService $recipe
     * @return Response
     */
    public function update(UpdateRequest $request, string $id, TransactionalApplicationService $service)
    {
        $serviceRequest = (new UpdateRecipeRequest())
            ->setId($id)
            ->setName($request->input('name'))
            ->setDescription($request->input('description'))
            ->setBody($request->input('body'));
        $service->execute($serviceRequest);

        return redirect()->route('recipes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string                          $id
     * @param TransactionalApplicationService $service
     * @return Response
     */
    public function destroy(string $id, TransactionalApplicationService $service)
    {
        $serviceRequest = (new DeleteRecipeRequest())->setId($id);
        $service->execute($serviceRequest);

        return redirect()->route('recipes.index');
    }
}
