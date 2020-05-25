<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Project;

use Webloyer\Domain\Model\Project\{
    Project,
    Projects,
};

class ProjectsDtoDataTransformer implements ProjectsDataTransformer
{
    /** @var Projects */
    private $projects;
    /** @var ProjectDtoDataTransformer */
    private $projectDataTransformer;

    public function __construct(ProjectDtoDataTransformer $projectDataTransformer)
    {
        $this->projectDataTransformer = $projectDataTransformer;
    }

    /**
     * @param Projects $projects
     * @return self
     */
    public function write(Projects $projects): self
    {
        $this->projects = $projects;
        return $this;
    }

    /**
     * @return array<int, object>
     */
    public function read()
    {
        return array_map(function (Project $project): object {
            return $this->projectDataTransformer->write($project)->read();
        }, $this->projects->toArray());
    }
}
