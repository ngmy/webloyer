<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployer;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileBuilderInterface;

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
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile
     */
    public function construct()
    {
        $this->fileBuilder->pathInfo();
        $this->fileBuilder->put();

        return $this->fileBuilder->getResult();
    }
}
