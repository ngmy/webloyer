<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Repositories\Deployment;

use InvalidArgumentException;
use Webloyer\Domain\Model\Deployment;
use Webloyer\Domain\Model\Project\ProjectId;
use Webloyer\Infra\Db\Eloquents\Deployment\Deployment as DeploymentOrm;
use Webloyer\Infra\Db\Eloquents\Deployment\MaxDeployment as MaxDeploymentOrm;

class DbDeploymentRepository implements Deployment\DeploymentRepository
{
    /** @var Deployment\DeploymentProjection */
    private $deploymentProjection;

    /**
     * @param Deployment\DeploymentProjection $deploymentProjection
     * @return void
     */
    public function __construct(Deployment\DeploymentProjection $deploymentProjection)
    {
        $this->deploymentProjection = $deploymentProjection;
    }

    /**
     * @param ProjectId $projectId
     * @return Deployment\DeploymentNumber
     * @see Deployment\DeploymentRepository::nextId()
     */
    public function nextId(ProjectId $projectId): Deployment\DeploymentNumber
    {
        // Lock the row until transaction is committed
        $maxDeploymentOrm = MaxDeploymentOrm::where('project_id', $projectId->value())
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
        return new Deployment\DeploymentNumber($maxDeploymentOrm->number);
    }

    /**
     * @return Deployment\Deployments
     * @see Deployment\DeploymentRepository::findAll()
     */
    public function findAll(): Deployment\Deployments
    {
        $deploymentArray = DeploymentOrm::orderBy('number')
            ->get()
            ->map(function (DeploymentOrm $deploymentOrm): Deployment\Deployment {
                return $deploymentOrm->toEntity();
            })
            ->toArray();
        return new Deployment\Deployments(...$deploymentArray);
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return Deployment\Deployments
     * @see Deployment\DeploymentRepository::findAllByPage()
     */
    public function findAllByPage(?int $page, ?int $perPage): Deployment\Deployments
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $deploymentArray = DeploymentOrm::orderBy('number')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (DeploymentOrm $deploymentOrm): Deployment\Deployment {
                return $deploymentOrm->toEntity();
            })
            ->toArray();
        return new Deployment\Deployments(...$deploymentArray);
    }

    /**
     * @param ProjectId                   $projectId
     * @param Deployment\DeploymentNumber $number
     * @return Deployment\Deployment|null
     * @see Deployment\DeploymentRepository::findById()
     */
    public function findById(ProjectId $projectId, Deployment\DeploymentNumber $number): ?Deployment\Deployment
    {
        $deploymentOrm = DeploymentOrm::ofId($projectId->value(), $number->value())->first();
        if (is_null($deploymentOrm)) {
            return null;
        }
        return $deploymentOrm->toEntity();
    }

    /**
     * @param Deployment\Deployment $deployment
     * @return void
     * @see Deployment\DeploymentRepository::remove()
     */
    public function remove(Deployment\Deployment $deployment): void
    {
        $deploymentOrm = DeploymentOrm::ofId($deployment->projectId(), $deployment->number())->first();
        if (is_null($deploymentOrm)) {
            return;
        }
        $deploymentOrm->delete();
    }

    /**
     * @param Deployment\Deployment $deployment
     * @return void
     * @see Deployment\DeploymentRepository::save()
     */
    public function save(Deployment\Deployment $deployment): void
    {
        $deploymentOrm = DeploymentOrm::firstOrNew([
            'project_id' => $deployment->projectId(),
            'number' => $deployment->number(),
        ]);
        $deployment->provide($deploymentOrm);
        $deploymentOrm->save();
        $this->deploymentProjection->project($deployment);
    }
}
