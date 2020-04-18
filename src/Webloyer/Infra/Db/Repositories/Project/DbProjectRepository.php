<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Repositories\Project;

use Str;
use Webloyer\Domain\Model\Project;
use Webloyer\Infra\Db\Eloquents\Project\Project as ProjectOrm;

class DbProjectRepository implements Project\ProjectRepository
{
    /**
     * @return Project\ProjectId
     * @see Project\ProjectRepository::nextId()
     */
    public function nextId(): Project\ProjectId
    {
        return new Project\ProjectId(Str::orderedUuid()->toString());
    }

    /**
     * @return Project\Projects
     * @see Project\ProjectRepository::findAll()
     */
    public function findAll(): Project\Projects
    {
        $recipeArray = ProjectOrm::orderBy('name')
            ->get()
            ->map(function (ProjectOrm $recipeOrm): Project\Project {
                return $recipeOrm->toEntity();
            })
            ->toArray();
        return new Project\Projects(...$recipeArray);
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

        $recipeArray = ProjectOrm::orderBy('name')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (ProjectOrm $recipeOrm): Project\Project {
                return $recipeOrm->toEntity();
            })
            ->toArray();
        return new Project\Projects(...$recipeArray);
    }

    /**
     * @param Project\ProjectId $id
     * @return Project\Project|null
     * @see Project\ProjectRepository::findById()
     */
    public function findById(Project\ProjectId $id): ?Project\Project
    {
        $recipeOrm = ProjectOrm::ofId($id->value())->first();
        if (is_null($recipeOrm)) {
            return null;
        }
        return $recipeOrm->toEntity();
    }

    /**
     * @param Project\Project $recipe
     * @return void
     * @see Project\ProjectRepository::remove()
     */
    public function remove(Project\Project $recipe): void
    {
        $recipeOrm = ProjectOrm::ofId($recipe->id())->first();
        if (is_null($recipeOrm)) {
            return;
        }
        $recipeOrm->delete();
    }

    /**
     * @param Project\Project $recipe
     * @return void
     * @see Project\ProjectRepository::save()
     */
    public function save(Project\Project $recipe): void
    {
        $recipeOrm = ProjectOrm::firstOrNew(['uuid' => $recipe->id()]);
        $recipe->provide($recipeOrm);
        $recipeOrm->save();
    }
}
