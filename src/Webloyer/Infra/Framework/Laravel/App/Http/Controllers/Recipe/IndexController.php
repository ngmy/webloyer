<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use App;
use Spatie\ViewModels\ViewModel;
use Webloyer\App\DataTransformer\Project\ProjectsDtoDataTransformer;
use Webloyer\App\Service\Recipe\GetRecipesService;
use Webloyer\Infra\App\DataTransformer\Recipe\RecipesLaravelLengthAwarePaginatorDataTransformer;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Recipe\IndexRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe\IndexViewModel;

class IndexController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param IndexRequest $request
     * @return ViewModel
     */
    public function __invoke(IndexRequest $request): ViewModel
    {
        assert($this->service instanceof GetRecipesService);
        assert($this->service->recipesDataTransformer() instanceof RecipesLaravelLengthAwarePaginatorDataTransformer);
        $this->service
            ->recipesDataTransformer()
            ->setPerPage(10)
            ->recipeDataTransformer()
            ->setProjectsDataTransformer(App::make(ProjectsDtoDataTransformer::class));
        $recipes = $this->service->execute();

        return (new IndexViewModel($recipes))->view('webloyer::recipe.index');
    }
}
