<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Ngmy\Webloyer\Common\QueryObject\AbstractCriteria;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;

class DeploymentCriteria extends AbstractCriteria
{
    private $priectId;

    public function __construct(ProjectId $projectId)
    {
        $this->setProjectId($projectId);
    }

    public function projectId()
    {
        return $this->projectId;
    }

    private function setProjectId(ProjectId $projectId)
    {
        $this->projectId = $projectId;
    }
}
