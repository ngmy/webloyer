<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Project;

use Webloyer\Domain\Model\Project\Projects;

class GetAllProjectsService extends ProjectService
{
    /**
     * @return Projects
     */
    public function execute($request = null)
    {
        return $this->projectRepository->findAll();
    }
}
