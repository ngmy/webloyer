<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use App;
use Webloyer\App\DataTransformer\Deployment\DeploymentDtoDataTransformer;
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
        $this->service
            ->projectsDataTransformer()
            ->setPerPage(10)
            ->projectDataTransformer()
            ->setDeploymentDataTransformer(App::make(DeploymentDtoDataTransformer::class));
        $projects = $this->service->execute();

        return (new IndexViewModel($projects))->view('webloyer::projects.index');
    }
}
