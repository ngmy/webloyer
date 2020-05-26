<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Project;

class GetProjectsService extends ProjectService
{
    /**
     * @return mixed
     */
    public function execute($request = null)
    {
        $projects = $this->projectRepository->findAll();
        return $this->projectsDataTransformer->write($projects)->read();
    }
}
