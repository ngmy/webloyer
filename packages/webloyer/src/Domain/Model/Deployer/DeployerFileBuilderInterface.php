<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployer;

interface DeployerFileBuilderInterface
{
    /**
     * Set a deployer file path info.
     *
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileBuilderInterface $this
     */
    public function pathInfo();

    /**
     * Put a deployer file.
     *
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileBuilderInterface $this
     */
    public function put();

    /**
     * Get a deployer file instance.
     *
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile
     */
    public function getResult();
}
