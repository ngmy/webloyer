<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment;

use App;
use Webloyer\App\DataTransformer\User\UserDtoDataTransformer;
use Webloyer\App\Service\Deployment\GetDeploymentsRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Deployment\IndexRequest;

class IndexController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param IndexRequest $request
     * @param string       $projectId
     * @return \Illuminate\Http\Response
     */
    public function __invoke(IndexRequest $request, string $projectId)
    {
        $serviceRequest = (new GetDeploymentsRequest())->setProjectId($projectId);
        $this->service
            ->deploymentsDataTransformer()
            ->setPerPage(10)
            ->deploymentDataTransformer()
            ->setUserDataTransformer(App::make(UserDtoDataTransformer::class));
        $deployments = $this->service->execute($serviceRequest);

        return view('webloyer::deployments.index')
            ->with('deployments', $deployments)
            ->with('projectId', $projectId);
    }
}
