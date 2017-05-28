<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Project;

use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;

interface ProjectRepositoryInterface
{
    public function allProjects();

    public function projectOfId(ProjectId $projectId);

    public function remove(Project $project);

    public function save(Project $project);
}
