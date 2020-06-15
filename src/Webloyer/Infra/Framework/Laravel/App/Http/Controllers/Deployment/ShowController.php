<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment;

use App;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Webloyer\App\DataTransformer\User\UserDtoDataTransformer;
use Webloyer\App\Service\Deployment\{
    GetDeploymentRequest,
    GetDeploymentService,
};
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Deployment\ShowRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Deployment\ShowViewModel;

class ShowController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param ShowRequest $request
     * @param string      $projectId
     * @param int         $number
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ShowRequest $request, string $projectId, int $number)
    {
        $serviceRequest = (new GetDeploymentRequest())
            ->setProjectId($projectId)
            ->setNumber($number);
        assert($this->service instanceof GetDeploymentService);
        $this->service
            ->deploymentDataTransformer()
            ->setUserDataTransformer(App::make(UserDtoDataTransformer::class));
        $deployment = $this->service->execute($serviceRequest);

        return (new ShowViewModel(
            $deployment,
            $projectId,
            new AnsiToHtmlConverter()
        ))->view('webloyer::deployments.show');
    }
}
