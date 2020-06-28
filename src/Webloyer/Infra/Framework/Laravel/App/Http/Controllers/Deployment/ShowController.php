<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment;

use App;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Spatie\ViewModels\ViewModel;
use Webloyer\App\DataTransformer\User\UserDtoDataTransformer;
use Webloyer\App\Service\Deployment\{
    GetDeploymentRequest,
    GetDeploymentService,
};
use Webloyer\Domain\Model\Deployment\DeploymentDoesNotExistException;
use Webloyer\Domain\Model\Project\ProjectDoesNotExistException;
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
     * @return ViewModel
     */
    public function __invoke(ShowRequest $request, string $projectId, int $number): ViewModel
    {
        $serviceRequest = (new GetDeploymentRequest())
            ->setProjectId($projectId)
            ->setNumber($number);

        assert($this->service instanceof GetDeploymentService);
        $this->service
            ->deploymentDataTransformer()
            ->setUserDataTransformer(App::make(UserDtoDataTransformer::class));

        try {
            $deployment = $this->service->execute($serviceRequest);
        } catch (ProjectDoesNotExistException $exception) {
            abort(404);
        } catch (DeploymentDoesNotExistException $exception) {
            abort(404);
        }

        return (new ShowViewModel(
            $deployment,
            $projectId,
            new AnsiToHtmlConverter()
        ))->view('webloyer::deployment.show');
    }
}
