<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Server;

use Webloyer\App\DataTransformer\Project\ProjectsDataTransformer;
use Webloyer\Domain\Model\Server\Server;

/**
 * @codeCoverageIgnore
 */
interface ServerDataTransformer
{
    /**
     * @param Server $server
     * @return self
     */
    public function write(Server $server): self;
    /**
     * @return mixed
     */
    public function read();
    /**
     * @param ProjectsDataTransformer $projectsDataTransformer
     * @return self
     */
    public function setProjectsDataTransformer(ProjectsDataTransformer $projectsDataTransformer): self;
}
