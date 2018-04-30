<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;

class DeploymentResponse
{
    private $deployment;

    public function __construct(Deployment $deployment)
    {
        $this->deployment = $deployment;
    }

    /**
     * Create deployment response from deployment object.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment $deployment
     * @return \Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc\DeploymentResponse
     */
    public static function fromDeployment(Deployment $deployment)
    {
        return new self($deployment);
    }

    /**
     * Convert deployment response to JSON.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment $deployment
     * @return \Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc\DeploymentResponse
     */
    public function toJson()
    {
        $deploymentResponse = [];

        $deploymentResponse['project_id'] = $this->deployment->projectId()->id();
        $deploymentResponse['deployment_id'] = $this->deployment->deploymentId()->id();
        $deploymentResponse['task'] = $this->deployment->task()->value();
        $deploymentResponse['status'] = $this->deployment->status()->value();
        $deploymentResponse['message'] = $this->deployment->message();
        $deploymentResponse['deployed_user_id'] = $this->deployment->deploymentId()->id();

        return json_encode($deploymentResponse);
    }
}
