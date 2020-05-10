<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Project;

use Common\App\Service\ApplicationService;
use InvalidArgumentException;
use Webloyer\Domain\Model\Project\{
    Project,
    ProjectId,
    ProjectRepository,
};

abstract class ProjectService implements ApplicationService
{
    /** @var ProjectRepository */
    protected $projectRepository;

    /**
     * @param ProjectRepository $projectRepository
     * @return void
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param ProjectId $id
     * @return Project
     * @throws InvalidArgumentException
     */
    protected function getNonNullProject(ProjectId $id): Project
    {
        $project = $this->projectRepository->findById($id);
        if (is_null($project)) {
            throw new InvalidArgumentException(
                'Project does not exists.' . PHP_EOL .
                'Id: ' . $id->value()
            );
        }
        return $project;
    }
}
