<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment;

use App;
use Spatie\ViewModels\ViewModel;
use Webloyer\App\DataTransformer\User\UserDtoDataTransformer;
use Webloyer\App\Service\Deployment\{
    GetDeploymentsRequest,
    GetDeploymentsService,
};
use Webloyer\Infra\App\DataTransformer\Deployment\DeploymentsLaravelLengthAwarePaginatorDataTransformer;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Deployment\IndexRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Deployment\IndexViewModel;

class IndexController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param IndexRequest $request
     * @param string       $projectId
     * @return ViewModel
     */
    public function __invoke(IndexRequest $request, string $projectId): ViewModel
    {
        $serviceRequest = (new GetDeploymentsRequest())->setProjectId($projectId);
        assert($this->service instanceof GetDeploymentsService);
        assert($this->service->deploymentsDataTransformer() instanceof DeploymentsLaravelLengthAwarePaginatorDataTransformer);
        $this->service
            ->deploymentsDataTransformer()
            ->setPerPage(10)
            ->deploymentDataTransformer()
            ->setUserDataTransformer(App::make(UserDtoDataTransformer::class));
        $deployments = $this->service->execute($serviceRequest);

        return (new IndexViewModel($deployments, $projectId))->view('webloyer::deployments.index');
    }
}
