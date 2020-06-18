<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use App;
use Spatie\ViewModels\ViewModel;
use Webloyer\App\DataTransformer\Recipe\RecipesDtoDataTransformer;
use Webloyer\App\DataTransformer\Server\ServerDtoDataTransformer;
use Webloyer\App\DataTransformer\User\UserDtoDataTransformer;
use Webloyer\App\Service\Project\{
    GetProjectRequest,
    GetProjectService,
};
use Webloyer\Domain\Model\Project\ProjectDoesNotExistException;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project\ShowViewModel;

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
        $serviceRequest = (new GetProjectRequest())->setId($id);

        assert($this->service instanceof GetProjectService);
        $this->service
            ->projectDataTransformer()
            ->setRecipesDataTransformer(App::make(RecipesDtoDataTransformer::class))
            ->setServerDataTransformer(App::make(ServerDtoDataTransformer::class))
            ->setUserDataTransformer(App::make(UserDtoDataTransformer::class));

        try {
            $project = $this->service->execute($serviceRequest);
        } catch (ProjectDoesNotExistException $exception) {
            abort(404);
        }

        return (new ShowViewModel($project))->view('webloyer::projects.show');
    }
}
