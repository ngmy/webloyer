<?php
declare(strict_types=1);

namespace App\Services\Deployment;

/**
 * Class DeployerFileDirector
 * @package App\Services\Deployment
 */
class DeployerFileDirector
{

    /**
     * @var DeployerFileBuilderInterface
     */
    protected DeployerFileBuilderInterface $fileBuilder;

    /**
     * DeployerFileDirector constructor.
     * @param DeployerFileBuilderInterface $fileBuilder
     */
    public function __construct(DeployerFileBuilderInterface $fileBuilder)
    {
        $this->fileBuilder = $fileBuilder;
    }

    /**
     * Construct a deployer file instance.
     *
     * @return DeployerFile
     */
    public function construct()
    {
        $this->fileBuilder->pathInfo();
        $this->fileBuilder->put();
        return $this->fileBuilder->getResult();
    }
}
