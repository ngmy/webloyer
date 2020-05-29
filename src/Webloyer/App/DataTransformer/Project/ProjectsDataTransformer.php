<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Project;

use Webloyer\Domain\Model\Project\Projects;

/**
 * @codeCoverageIgnore
 */
interface ProjectsDataTransformer
{
    /**
     * @param Projects $projects
     * @return self
     */
    public function write(Projects $projects): self;
    /**
     * @return mixed
     */
    public function read();
    /**
     * @return ProjectDataTransformer
     */
    public function projectDataTransformer(): ProjectDataTransformer;
}
