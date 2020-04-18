<?php

declare(strict_types=1);

namespace Webloyer\App\Project;

use InvalidArgumentException;
use Webloyer\App\Project\Commands;
use Webloyer\Domain\Model\Project;

class ProjectService
{
    /** @var Project\ProjectRepository */
    private $projectRepository;

    /**
     * @param Project\ProjectRepository $projectRepository
     * @return void
     */
    public function __construct(Project\ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @return Project\Projects
     */
    public function getAllProjects(): Project\Projects
    {
        return $this->projectRepository->findAll();
    }

    /**
     * @param Commands\GetProjectsCommand $command
     * @return Project\Projects
     */
    public function getProjects(Commands\GetProjectsCommand $command): Project\Projects
    {
        return $this->projectRepository->findAllByPage($command->getPage(), $command->getPerPage());
    }

    /**
     * @param Commands\GetProjectCommand $command
     * @return Project\Project
     */
    public function getProject(Commands\GetProjectCommand $command): Project\Project
    {
        $id = new Project\ProjectId($command->getId());
        return $this->getNonNullProject($id);
    }

    /**
     * @param Commands\CreateProjectCommand $command
     * @return void
     */
    public function createProject(Commands\CreateProjectCommand $command): void
    {
        $project = Project\Project::of(
            $this->projectRepository->nextId()->value(),
            $command->getName(),
            $command->getRecipeIds(),
            $command->getServerId(),
            $command->getRepositoryUrl(),
            $command->getStageName(),
            $command->getDeployPath(),
            $command->getEmailNotificationRecipient(),
            $command->getDeploymentKeepDays(),
            $command->getKeepLastDeployment(),
            $command->getDeploymentKeepMaxNumber(),
            $command->getGithubWebhookSecret(),
            $command->getGithubWebhookExecutor()
        );
        $this->projectRepository->save($project);
    }

    /**
     * @param Commands\UpdateProjectCommand $command
     * @return void
     */
    public function updateProject(Commands\UpdateProjectCommand $command): void
    {
        $id = new Project\ProjectId($command->getId());
        $project = $this->getNonNullProject($id)
            ->changeName($command->getName())
            ->changeRecipes(...$command->getRecipeIds())
            ->changeServer($command->getServerId())
            ->changeRepositoryUrl($command->getRepositoryUrl())
            ->changeStageName($command->getStageName())
            ->changeDeployPath($command->getDeployPath())
            ->changeEmailNotificationRecipient($command->getEmailNotificationRecipient())
            ->changeDeploymentKeepDays($command->getDeploymentKeepDays())
            ->changeKeepLastDeployment($command->getKeepLastDeployment())
            ->changeDeploymentKeepMaxNumber($command->getDeploymentKeepMaxNumber())
            ->changeGithubWebhookSecret($command->getGithubWebhookSecret())
            ->changeGithubWebhookExecutor($command->getGithubWebhookExecutor());
        $this->projectRepository->save($project);
    }

    /**
     * @param Commands\DeleteProjectCommand $command
     * @return void
     */
    public function deleteProject(Commands\DeleteProjectCommand $command): void
    {
        $id = new Project\ProjectId($command->getId());
        $project = $this->getNonNullProject($id);
        $this->projectRepository->remove($project);
    }

    /**
     * @param Project\ProjectId $id
     * @return Project\Project
     * @throws InvalidArgumentException
     */
    private function getNonNullProject(Project\ProjectId $id): Project\Project
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
