<?php

namespace App\Services\Deployment;

class DeployerFileDirector
{
    protected $fileBuilder;

    public function __construct(DeployerFileBuilderInterface $fileBuilder)
    {
        $this->fileBuilder = $fileBuilder;
    }

    /**
     * Construct a deployer file instance.
     *
     * @return \App\Services\Deployment\DeployerFile
     */
    public function construct()
    {
        $this->fileBuilder->pathInfo();
        $this->fileBuilder->put();

        return $this->fileBuilder->getResult();
    }
}
