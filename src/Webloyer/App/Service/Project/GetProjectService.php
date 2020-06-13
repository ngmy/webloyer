<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Project;

use Webloyer\Domain\Model\Project\ProjectId;

class GetProjectService extends ProjectService
{
    /**
     * @param GetProjectRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $id = new ProjectId($request->getId());
        $project = $this->getNonNullProject($id);
        return $this->projectDataTransformer->write($project)->read();
    }
}
