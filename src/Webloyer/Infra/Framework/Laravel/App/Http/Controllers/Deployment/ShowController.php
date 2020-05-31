<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment;

use Webloyer\App\Service\Deployment\GetDeploymentRequest;

class ShowController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $projectid
     * @param int    $number
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $projectId, int $number)
    {
        $serviceRequest = (new GetDeploymentRequest())
            ->setProjectId($projectId)
            ->setNumber($number);
        $deployment = $this->service->execute($serviceRequest);

        return view('webloyer::deployments.show')->with('deployment', $deployment);
    }
}
