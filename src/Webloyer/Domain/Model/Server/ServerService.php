<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Server;

use Webloyer\Domain\Model\Project\ProjectRepository;
use Webloyer\Domain\Model\Project\Projects;

class ServerService
{
    /** @var ProjectRepository */
    private $projectRepository;

    /**
     * @param ProjectRepository $projectRepository
     * @return void
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param ServerId $serverId
     * @return Projects
     */
    public function projectsFrom(ServerId $serverId): Projects
    {
        return $this->projectRepository->findAllByServerId($serverId);
    }
}
