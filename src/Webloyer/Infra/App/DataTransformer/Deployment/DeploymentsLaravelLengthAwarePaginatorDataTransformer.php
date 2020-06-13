<?php

declare(strict_types=1);

namespace Webloyer\Infra\App\DataTransformer\Deployment;

use Illuminate\Pagination\LengthAwarePaginator;
use Webloyer\App\DataTransformer\Deployment\{
    DeploymentDataTransformer,
    DeploymentsDataTransformer,
    DeploymentsDtoDataTransformer,
};
use Webloyer\Domain\Model\Deployment\Deployments;

class DeploymentsLaravelLengthAwarePaginatorDataTransformer implements DeploymentsDataTransformer
{
    /** @var Deployments */
    private $deployments;
    /** @var DeploymentsDtoDataTransformer */
    private $deploymentsDataTransformer;
    /** @var int */
    private $perPage = 10;
    /** @var int */
    private $currentPage;
    /** @var array<string, string> */
    private $options;

    /**
     * @param DeploymentsDtoDataTransformer $deploymentsDataTransformer
     * @return void
     */
    public function __construct(DeploymentsDtoDataTransformer $deploymentsDataTransformer)
    {
        $this->deploymentsDataTransformer = $deploymentsDataTransformer;
        $this->currentPage = LengthAwarePaginator::resolveCurrentPage();
        $this->options = [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ];
    }

    /**
     * @param int $perPage
     * @return self
     */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @param Deployments $deployments
     * @return self
     */
    public function write(Deployments $deployments): self
    {
        $this->deployments = $deployments;
        return $this;
    }

    /**
     * @return LengthAwarePaginator<object>
     */
    public function read()
    {
        $deployments = $this->deploymentsDataTransformer->write($this->deployments)->read();
        return new LengthAwarePaginator(
            array_slice(
                $deployments,
                $this->perPage * ($this->currentPage - 1),
                $this->perPage
            ),
            count($deployments),
            $this->perPage,
            $this->currentPage,
            $this->options
        );
    }

    /**
     * @return DeploymentDataTransformer
     */
    public function deploymentDataTransformer(): DeploymentDataTransformer
    {
        return $this->deploymentsDataTransformer->deploymentDataTransformer();
    }
}
