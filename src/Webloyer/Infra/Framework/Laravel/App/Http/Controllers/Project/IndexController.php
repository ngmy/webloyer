<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use App;
use Spatie\ViewModels\ViewModel;
use Webloyer\App\DataTransformer\Deployment\DeploymentDtoDataTransformer;
use Webloyer\App\DataTransformer\Project\ProjectsDtoDataTransformer;
use Webloyer\App\Service\Project\GetProjectsService;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Project\IndexRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project\IndexViewModel;

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
        assert($this->service instanceof GetProjectsService);
        assert($this->service->projectsDataTransformer() instanceof ProjectsDtoDataTransformer);
        $this->service
            ->projectsDataTransformer()
            ->projectDataTransformer()
            ->setDeploymentDataTransformer(App::make(DeploymentDtoDataTransformer::class));
        $projects = $this->service->execute();

        return (new IndexViewModel($projects))
            ->setPerPage(10)
            ->view('webloyer::project.index');
    }
}
