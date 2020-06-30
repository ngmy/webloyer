<?php

declare(strict_types=1);

namespace Webloyer\Infra\Domain\Model\Deployment;

use InvalidArgumentException;
use RuntimeException;
use Webloyer\Domain\Model\Deployment\{
    Deployment,
    DeploymentNumber,
    DeploymentRepository,
    DeploymentSpecification,
    Deployments,
};
use Webloyer\Domain\Model\Project\Project;
use Webloyer\Infra\Persistence\Eloquent\Models\{
    Deployment as DeploymentOrm,
    MaxDeployment as MaxDeploymentOrm,
    Project as ProjectOrm,
};

class EloquentDeploymentRepository implements DeploymentRepository
{
    /**
     * @param Project $project
     * @return DeploymentNumber
     * @see DeploymentRepository::nextId()
     */
    public function nextId(Project $project): DeploymentNumber
    {
        // Lock the row until transaction is committed
        $projectOrm = ProjectOrm::ofId($project->id())->first();
        $maxDeploymentOrm = MaxDeploymentOrm::where('project_id', $projectOrm->id)
            ->lockForUpdate()
            ->first();
        if (is_null($maxDeploymentOrm)) {
            throw new InvalidArgumentException(
                'Max deployment does not exists.' . PHP_EOL .
                'Project Id: ' . $project->id()
            );
        }
        $maxDeploymentOrm->number++;
        $maxDeploymentOrm->save();
        return new DeploymentNumber($maxDeploymentOrm->number);
    }

    /**
     * @param Project $project
     * @return Deployments
     * @see DeploymentRepository::findAllByProject()
     */
    public function findAllByProject(Project $project): Deployments
    {
        $deploymentArray = DeploymentOrm::ofProjectId($project->id())
            ->orderBy('number', 'desc')
            ->get()
            ->map(function (DeploymentOrm $deploymentOrm): Deployment {
                return $deploymentOrm->toEntity();
            })
            ->toArray();
        return new Deployments(...$deploymentArray);
    }

    /**
     * @param Project   $project
     * @param int|null  $page
     * @param int|null  $perPage
     * @return Deployments
     * @see DeploymentRepository::findAllByProjectAndPage()
     */
    public function findAllByProjectAndPage(Project $project, ?int $page, ?int $perPage): Deployments
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $deploymentArray = DeploymentOrm::ofProjectId($project->id())
            ->orderBy('number', 'desc')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (DeploymentOrm $deploymentOrm): Deployment {
                return $deploymentOrm->toEntity();
            })
            ->toArray();
        return new Deployments(...$deploymentArray);
    }

    /**
     * @param Project          $project
     * @param DeploymentNumber $number
     * @return Deployment|null
     * @see DeploymentRepository::findById()
     */
    public function findById(Project $project, DeploymentNumber $number): ?Deployment
    {
        $deploymentOrm = DeploymentOrm::ofId($project->id(), $number->value())->first();
        if (is_null($deploymentOrm)) {
            return null;
        }
        return $deploymentOrm->toEntity();
    }

    /**
     * @param Project $project
     * @return Deployment|null
     */
    public function findLastByProject(Project $project): ?Deployment
    {
        $deploymentOrm = DeploymentOrm::ofProjectId($project->id())
            ->orderBy('number', 'desc')
            ->first();
        if (is_null($deploymentOrm)) {
            return null;
        }
        return $deploymentOrm->toEntity();
    }

    /**
     * @param Deployment $deployment
     * @return void
     * @see DeploymentRepository::remove()
     */
    public function remove(Deployment $deployment): void
    {
        $deploymentOrm = DeploymentOrm::ofId($deployment->projectId(), $deployment->number())->first();
        if (is_null($deploymentOrm)) {
            return;
        }
        $deploymentOrm->delete();
    }

    /**
     * @param Deployments $deployments
     * @return void
     * @see DeploymentRepository::removeAll()
     */
    public function removeAll(Deployments $deployments): void
    {
        foreach ($deployments->toArray() as $deployment) {
            $this->remove($deployment);
        }
    }

    /**
     * @param Deployment $deployment
     * @return void
     * @see DeploymentRepository::save()
     */
    public function save(Deployment $deployment): void
    {
        $projectOrm = ProjectOrm::ofId($deployment->projectId())->first();
        if (is_null($projectOrm)) {
            throw new RuntimeException();
        }
        $deploymentOrm = DeploymentOrm::firstOrNew([
            'project_id' => $projectOrm->id,
            'number' => $deployment->number(),
        ]);
        $deployment->provide($deploymentOrm);
        $deploymentOrm->save();

        $deployment->setSurrogateId($deploymentOrm->id)
            ->setCreatedAt($deploymentOrm->created_at)
            ->setUpdatedAt($deploymentOrm->updated_at);
    }

    /**
     * @param DeploymentSpecification $spec
     * @return Deployments
     */
    public function satisfyingDeployments(DeploymentSpecification $spec): Deployments
    {
        return $spec->satisfyingElementsFrom($this);
    }
}
