<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Project;

class GetAllProjectsService extends ProjectService
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
