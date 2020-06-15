<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use App;
use Webloyer\App\DataTransformer\Deployment\DeploymentDtoDataTransformer;
use Webloyer\App\Service\Project\GetProjectsService;
use Webloyer\Infra\App\DataTransformer\Project\ProjectsLaravelLengthAwarePaginatorDataTransformer;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Project\IndexRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project\IndexViewModel;

class IndexController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param IndexRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(IndexRequest $request)
    {
        assert($this->service instanceof GetProjectsService);
        assert($this->service->projectsDataTransformer() instanceof ProjectsLaravelLengthAwarePaginatorDataTransformer);
        $this->service
            ->projectsDataTransformer()
            ->setPerPage(10)
            ->projectDataTransformer()
            ->setDeploymentDataTransformer(App::make(DeploymentDtoDataTransformer::class));
        $projects = $this->service->execute();

        return (new IndexViewModel($projects))->view('webloyer::projects.index');
    }
}
