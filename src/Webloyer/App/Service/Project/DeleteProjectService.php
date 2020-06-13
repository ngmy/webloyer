<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Project;

use Webloyer\Domain\Model\Project\ProjectId;

class DeleteProjectService extends ProjectService
{
    /**
     * @param DeleteProjectRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $id = new ProjectId($request->getId());
        $project = $this->getNonNullProject($id);
        $this->projectRepository->remove($project);
    }
}
