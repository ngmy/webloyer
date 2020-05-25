<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Project;

use Webloyer\Domain\Model\Project\Project;

/**
 * @codeCoverageIgnore
 */
interface ProjectDataTransformer
{
    /**
     * @param Project $project
     * @return self
     */
    public function write(Project $project): self;
    /**
     * @return mixed
     */
    public function read();
}
