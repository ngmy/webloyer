<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project;

use Illuminate\Http\RedirectResponse;
use Webloyer\App\Service\Project\DeleteProjectRequest;
use Webloyer\Domain\Model\Project\ProjectDoesNotExistException;

class DestroyController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function __invoke(string $id): RedirectResponse
    {
        $serviceRequest = (new DeleteProjectRequest())->setId($id);

        assert(!is_null($this->service));

        try {
            $this->service->execute($serviceRequest);
        } catch (ProjectDoesNotExistException $exception) {
            abort(404);
        }

        return redirect()->route('projects.index');
    }
}
