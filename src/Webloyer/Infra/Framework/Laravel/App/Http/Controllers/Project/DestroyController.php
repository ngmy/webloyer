<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use Webloyer\App\Service\Project\DeleteProjectRequest;

class DestroyController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id)
    {
        $serviceRequest = (new DeleteProjectRequest())->setId($id);
        $this->service->execute($serviceRequest);

        return redirect()->route('projects.index');
    }
}
