<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use App;
use Spatie\ViewModels\ViewModel;
use Webloyer\App\DataTransformer\Project\ProjectsDtoDataTransformer;
use Webloyer\App\Service\Recipe\{
    GetRecipeRequest,
    GetRecipeService,
};
use Webloyer\Domain\Model\Recipe\RecipeDoesNotExistException;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe\ShowViewModel;

class ShowController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return ViewModel
     */
    public function __invoke(string $id): ViewModel
    {
        $serviceRequest = (new GetRecipeRequest())->setId($id);

        assert($this->service instanceof GetRecipeService);
        $this->service
            ->recipeDataTransformer()
            ->setProjectsDataTransformer(App::make(ProjectsDtoDataTransformer::class));

        try {
            $recipe = $this->service->execute($serviceRequest);
        } catch (RecipeDoesNotExistException $exception) {
            abort(404);
        }

        return (new ShowViewModel($recipe))->view('webloyer::recipes.show');
    }
}
