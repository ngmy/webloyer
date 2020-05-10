<?php

declare(strict_types=1);

namespace Webloyer\Infra\Domain\Model\Project;

use Common\Domain\Model\Identity\IdGenerator;
use Webloyer\Domain\Model\Project;
use Webloyer\Infra\Persistence\Eloquent\Models\Project as ProjectOrm;

class EloquentProjectRepository implements Project\ProjectRepository
{
    /** @var IdGenerator */
    private $idGenerator;

    public function __construct(IdGenerator $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    /**
     * @return Project\ProjectId
     * @see Project\ProjectRepository::nextId()
     */
    public function nextId(): Project\ProjectId
    {
        return new Project\ProjectId($this->idGenerator->generate());
    }

    /**
     * @return Project\Projects
     * @see Project\ProjectRepository::findAll()
     */
    public function findAll(): Project\Projects
    {
        $projectArray = ProjectOrm::orderBy('name')
            ->get()
            ->map(function (ProjectOrm $projectOrm): Project\Project {
                return $projectOrm->toEntity();
            })
            ->toArray();
        return new Project\Projects(...$projectArray);
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Project\Projects
     * @see Project\ProjectRepository::findAllByPage()
     */
    public function findAllByPage(?int $page, ?int $perPage): Project\Projects
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $projectArray = ProjectOrm::orderBy('name')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (ProjectOrm $projectOrm): Project\Project {
                return $projectOrm->toEntity();
            })
            ->toArray();
        return new Project\Projects(...$projectArray);
    }

    /**
     * @param Project\ProjectId $id
     * @return Project\Project|null
     * @see Project\ProjectRepository::findById()
     */
    public function findById(Project\ProjectId $id): ?Project\Project
    {
        $projectOrm = ProjectOrm::ofId($id->value())->first();
        if (is_null($projectOrm)) {
            return null;
        }
        return $projectOrm->toEntity();
    }

    /**
     * @param Project\Project $project
     * @return void
     * @see Project\ProjectRepository::remove()
     */
    public function remove(Project\Project $project): void
    {
        $projectOrm = ProjectOrm::ofId($project->id())->first();
        if (is_null($projectOrm)) {
            return;
        }
        $projectOrm->delete();
    }

    /**
     * @param Project\Project $project
     * @return void
     * @see Project\ProjectRepository::save()
     */
    public function save(Project\Project $project): void
    {
        $projectOrm = ProjectOrm::firstOrNew(['uuid' => $project->id()]);
        if ($projectOrm->wasRecentlyCreated) {
            $projectOrm->maxDeployment()->create(['project_id' => $project->surrogateId()]);
        }
        $project->provide($projectOrm);
        $projectOrm->save();
    }
}
