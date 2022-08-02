<?php
declare(strict_types=1);

namespace App\Services\Deployment;

/**
 * Interface DeployerFileBuilderInterface
 * @package App\Services\Deployment
 */
interface DeployerFileBuilderInterface
{
    /**
     * Set a deployer file path info.
     *
     * @return DeployerFileBuilderInterface $this
     */
    public function pathInfo();

    /**
     * Put a deployer file.
     *
     * @return DeployerFileBuilderInterface $this
     */
    public function put();

    /**
     * Get a deployer file instance.
     *
     * @return DeployerFile
     */
    public function getResult();
}
