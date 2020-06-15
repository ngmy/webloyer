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
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Infra\Persistence\Eloquent\Models\{
    Deployment as DeploymentOrm,
    MaxDeployment as MaxDeploymentOrm,
    Project as ProjectOrm,
};

class EloquentDeploymentRepository implements DeploymentRepository
{
    /**
     * @param ProjectId $projectId
     * @return DeploymentNumber
     * @see DeploymentRepository::nextId()
     */
    public function nextId(ProjectId $projectId): DeploymentNumber
    {
        // Lock the row until transaction is committed
        $projectOrm = ProjectOrm::ofId($projectId->value())->first();
        $maxDeploymentOrm = MaxDeploymentOrm::where('project_id', $projectOrm->id)
            ->lockForUpdate()
            ->first();
        if (is_null($maxDeploymentOrm)) {
            throw new InvalidArgumentException(
                'Max deployment does not exists.' . PHP_EOL .
                'Project Id: ' . $projectId->value()
            );
        }
        $maxDeploymentOrm->number++;
        $maxDeploymentOrm->save();
        return new DeploymentNumber($maxDeploymentOrm->number);
    }

    /**
     * @param ProjectId $projectId
     * @return Deployments
     * @see DeploymentRepository::findAllByProjectId()
     */
    public function findAllByProjectId(ProjectId $projectId): Deployments
    {
        $deploymentArray = DeploymentOrm::ofProjectId($projectId->value())
            ->orderBy('number', 'desc')
            ->get()
            ->map(function (DeploymentOrm $deploymentOrm): Deployment {
                return $deploymentOrm->toEntity();
            })
            ->toArray();
        return new Deployments(...$deploymentArray);
    }

    /**
     * @param ProjectId $projectId
     * @param int|null  $page
     * @param int|null  $perPage
     * @return Deployments
     * @see DeploymentRepository::findAllByProjectIdAndPage()
     */
    public function findAllByProjectIdAndPage(ProjectId $projectId, ?int $page, ?int $perPage): Deployments
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $deploymentArray = DeploymentOrm::ofProjectId($projectId->value())
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
     * @param ProjectId                   $projectId
     * @param DeploymentNumber $number
     * @return Deployment|null
     * @see DeploymentRepository::findById()
     */
    public function findById(ProjectId $projectId, DeploymentNumber $number): ?Deployment
    {
        $deploymentOrm = DeploymentOrm::ofId($projectId->value(), $number->value())->first();
        if (is_null($deploymentOrm)) {
            return null;
        }
        return $deploymentOrm->toEntity();
    }

    /**
     * @param ProjectId $projectId
     * @return Deployment|null
     */
    public function findLastByProjectId(ProjectId $projectId): ?Deployment
    {
        $deploymentOrm = DeploymentOrm::ofProjectId($projectId->value())
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
