<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use App;
use Webloyer\App\DataTransformer\Recipe\RecipesDtoDataTransformer;
use Webloyer\App\DataTransformer\Server\ServerDtoDataTransformer;
use Webloyer\App\DataTransformer\User\UserDtoDataTransformer;
use Webloyer\App\Service\Project\GetProjectRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project\ShowViewModel;

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
        $serviceRequest = (new GetProjectRequest())->setId($id);
        $this->service
            ->projectDataTransformer()
            ->setRecipesDataTransformer(App::make(RecipesDtoDataTransformer::class))
            ->setServerDataTransformer(App::make(ServerDtoDataTransformer::class))
            ->setUserDataTransformer(App::make(UserDtoDataTransformer::class));
        $project = $this->service->execute($serviceRequest);

        return (new ShowViewModel($project))->view('webloyer::projects.show');
    }
}
