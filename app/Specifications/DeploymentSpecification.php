<?php
declare(strict_types=1);

namespace App\Specifications;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DeploymentSpecification
 * @package App\Specifications
 */
class DeploymentSpecification
{
    /**
     * Get elements that satisfy the specification.
     *
     * @param \Illuminate\Database\Eloquent\Model $project
     * @return \Illuminate\Support\Collection
     */
    public function satisfyingElementsFrom(Model $project)
    {
        return $project->getDeployments();
    }
}
