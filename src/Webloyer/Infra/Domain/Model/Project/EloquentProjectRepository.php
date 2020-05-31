<?php

declare(strict_types=1);

namespace Webloyer\Infra\Domain\Model\Project;

use Common\Domain\Model\Identity\IdGenerator;
use Webloyer\Domain\Model\Project\{
    Project,
    ProjectId,
    ProjectRepository,
    Projects,
};
use Webloyer\Domain\Model\Recipe\RecipeId;
use Webloyer\Infra\Persistence\Eloquent\Models\{
    Project as ProjectOrm,
    Recipe as RecipeOrm,
};

class EloquentProjectRepository implements ProjectRepository
{
    /** @var IdGenerator */
    private $idGenerator;

    public function __construct(IdGenerator $idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    /**
     * @return ProjectId
     * @see ProjectRepository::nextId()
     */
    public function nextId(): ProjectId
    {
        return new ProjectId($this->idGenerator->generate());
    }

    /**
     * @return Projects
     * @see ProjectRepository::findAll()
     */
    public function findAll(): Projects
    {
        $projectArray = ProjectOrm::orderBy('name')
            ->get()
            ->map(function (ProjectOrm $projectOrm): Project {
                return $projectOrm->toEntity();
            })
            ->toArray();
        return new Projects(...$projectArray);
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Projects
     * @see ProjectRepository::findAllByPage()
     */
    public function findAllByPage(?int $page, ?int $perPage): Projects
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $projectArray = ProjectOrm::orderBy('name')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (ProjectOrm $projectOrm): Project {
                return $projectOrm->toEntity();
            })
            ->toArray();
        return new Projects(...$projectArray);
    }

    /**
     * @param RecipeId $recipeId
     * @return Projects
     */
    public function findAllByRecipeId(RecipeId $recipeId): Projects
    {
        $recipe = RecipeOrm::ofId($recipeId->value())->first();

        if (is_null($recipe)) {
            return Projects::empty();
        }

        $projectArray = $recipe->projects
            ->map(function (ProjectOrm $projectOrm): Project {
                return $projectOrm->toEntity();
            })
            ->toArray();
        return new Projects(...$projectArray);
    }

    /**
     * @param ProjectId $id
     * @return Project|null
     * @see ProjectRepository::findById()
     */
    public function findById(ProjectId $id): ?Project
    {
        $projectOrm = ProjectOrm::ofId($id->value())->first();
        if (is_null($projectOrm)) {
            return null;
        }
        return $projectOrm->toEntity();
    }

    /**
     * @param Project $project
     * @return void
     * @see ProjectRepository::remove()
     */
    public function remove(Project $project): void
    {
        $projectOrm = ProjectOrm::ofId($project->id())->first();
        if (is_null($projectOrm)) {
            return;
        }
        $projectOrm->delete();
        $projectOrm->maxDeployment()->delete();
    }

    /**
     * @param Project $project
     * @return void
     * @see ProjectRepository::save()
     */
    public function save(Project $project): void
    {
        $projectOrm = ProjectOrm::firstOrNew(['uuid' => $project->id()]);
        $project->provide($projectOrm);
        $projectOrm->save();
        if ($projectOrm->wasRecentlyCreated) {
            $projectOrm->maxDeployment()->create(['project_id' => $projectOrm->id]);
        }
    }
}
