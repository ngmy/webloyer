<?php

namespace App\Repositories\Deployment;

use App\Repositories\RepositoryInterface;

interface DeploymentInterface extends RepositoryInterface
{
    /**
     * Get a deployment by project id and number.
     *
     * @param int $projectId Project id
     * @param int $number    Deployment number
     * @return mixed
     */
    public function byProjectIdAndNumber($projectId, $number);

    /**
     * Get deployments by project id.
     *
     * @param int $projectId Project id
     * @param int $page      Page number
     * @param int $limit     Number of deployments per page
     * @return mixed
     */
    public function byProjectId($projectId, $page = 1, $limit = 10);
}
