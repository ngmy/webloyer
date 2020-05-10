<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Project;

use Webloyer\Domain\Model\Project\{
    Project,
    ProjectId,
};

class GetProjectService extends ProjectService
{
    /**
     * @param GetProjectRequest $request
     * @return Project
     */
    public function execute($request = null)
    {
        $id = new ProjectId($request->getId());
        return $this->getNonNullProject($id);
    }
}
