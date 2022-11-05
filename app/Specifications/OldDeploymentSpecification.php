<?php
declare(strict_types=1);

namespace App\Specifications;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use Illuminate\Support\Collection;

/**
 * Class OldDeploymentSpecification
 * @package App\Specifications
 */
class OldDeploymentSpecification extends DeploymentSpecification
{
    /**
     * @var DateTime
     */
    protected DateTime $currentDate;

    /**
     * OldDeploymentSpecification constructor.
     * @param DateTime $currentDate
     */
    public function __construct(DateTime $currentDate)
    {
        $this->currentDate = $currentDate;
    }

    /**
     * Get elements that satisfy the specification.
     *
     * @param Model $project
     * @return Collection
     */
    public function satisfyingElementsFrom(Model $project)
    {
        if ($project->getDeployments()->isEmpty()) {
            return collect([]);
        }

        $oldDeployments = collect([]);
        $pastDaysToKeepDeployments = collect([]);
        $pastNumToKeepDeployments = collect([]);

        if (!is_null($project->days_to_keep_deployments)) {
            $currentDate = clone $this->currentDate;
            $date = $currentDate->modify('-'.$project->days_to_keep_deployments.' days');
            $pastDaysToKeepDeployments = $project->getDeploymentsWhereCreatedAtBefore($date);

            if ($project->keep_last_deployment && $pastDaysToKeepDeployments->contains($project->getLastDeployment())) {
                $pastDaysToKeepDeployments->shift();
            }
        }

        if (!is_null($project->max_number_of_deployments_to_keep)) {
            $number = $project->getLastDeployment()->number - $project->max_number_of_deployments_to_keep + 1;
            $pastNumToKeepDeployments = $project->getDeploymentsWhereNumberBefore($number);
        }

        return $oldDeployments->merge($pastDaysToKeepDeployments)
            ->merge($pastNumToKeepDeployments)
            ->sortByDesc('number')
            ->unique('number')
            ->values();
    }
}
