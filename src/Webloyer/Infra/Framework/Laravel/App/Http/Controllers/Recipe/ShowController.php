<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe;

use App;
use Webloyer\App\DataTransformer\Project\ProjectsDtoDataTransformer;
use Webloyer\App\Service\Recipe\{
    GetRecipeRequest,
    GetRecipeService,
};
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Recipe\ShowViewModel;

class ShowController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id)
    {
        $serviceRequest = (new GetRecipeRequest())->setId($id);
        assert($this->service instanceof GetRecipeService);
        $this->service
            ->recipeDataTransformer()
            ->setProjectsDataTransformer(App::make(ProjectsDtoDataTransformer::class));
        $recipe = $this->service->execute($serviceRequest);

        return (new ShowViewModel($recipe))->view('webloyer::recipes.show');
    }
}
