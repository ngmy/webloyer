<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Project;

use Common\App\Service\ApplicationService;
use InvalidArgumentException;
use Webloyer\App\DataTransformer\Project\{
    ProjectDataTransformer,
    ProjectsDataTransformer,
};
use Webloyer\Domain\Model\Project\{
    Project,
    ProjectDoesNotExistException,
    ProjectId,
    ProjectRepository,
};

abstract class ProjectService implements ApplicationService
{
    /** @var ProjectRepository */
    protected $projectRepository;
    /** @var ProjectDataTransformer */
    protected $projectDataTransformer;
    /** @var ProjectsDataTransformer */
    protected $projectsDataTransformer;

    /**
     * @param ProjectRepository       $projectRepository
     * @param ProjectDataTransformer  $projectDataTransformer
     * @param ProjectsDataTransformer $projectsDataTransformer
     * @return void
     */
    public function __construct(
        ProjectRepository $projectRepository,
        ProjectDataTransformer $projectDataTransformer,
        ProjectsDataTransformer $projectsDataTransformer
    ) {
        $this->projectRepository = $projectRepository;
        $this->projectDataTransformer = $projectDataTransformer;
        $this->projectsDataTransformer = $projectsDataTransformer;
    }

    /**
     * @return ProjectDataTransformer
     */
    public function projectDataTransformer(): ProjectDataTransformer
    {
        return $this->projectDataTransformer;
    }

    /**
     * @return ProjectsDataTransformer
     */
    public function projectsDataTransformer(): ProjectsDataTransformer
    {
        return $this->projectsDataTransformer;
    }

    /**
     * @param ProjectId $id
     * @return Project
     * @throws ProjectDoesNotExistException
     */
    protected function getNonNullProject(ProjectId $id): Project
    {
        $project = $this->projectRepository->findById($id);
        if (is_null($project)) {
            throw new ProjectDoesNotExistException(
                'Project does not exist.' . PHP_EOL .
                'Id: ' . $id->value()
            );
        }
        return $project;
    }
}
