<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\Project;

/**
 * @codeCoverageIgnore
 */
interface ProjectRepository
{
    /**
     * @return ProjectId
     */
    public function nextId(): ProjectId;
    /**
     * @return Projects
     */
    public function findAll(): Projects;
    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Projects
     */
    public function findAllByPage(?int $page, ?int $perPage): Projects;
    /**
     * @param ProjectId $id
     * @return Project|null
     */
    public function findById(ProjectId $id): ?Project;
    /**
     * @param Project $project
     * @return void
     */
    public function remove(Project $project): void;
    /**
     * @param Project $project
     * @return void
     */
    public function save(Project $project): void;
}
