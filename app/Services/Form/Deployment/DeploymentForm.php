<?php
declare(strict_types=1);

namespace App\Services\Form\Deployment;

use App\Models\Project;
use App\Services\Validation\ValidableInterface;
use App\Services\Deployment\DeployCommanderInterface;
use App\Repositories\Project\ProjectInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class DeploymentForm
 * @package App\Services\Form\Deployment
 */
class DeploymentForm
{
    /**
     * @var ValidableInterface
     */
    protected ValidableInterface $validator;

    /**
     * @var ProjectInterface
     */
    protected ProjectInterface $project;

    /**
     * @var DeployCommanderInterface
     */
    protected DeployCommanderInterface $deployCommander;

    /**
     * Create a new form service instance.
     *
     * @param ValidableInterface $validator
     * @param ProjectInterface $project
     * @param DeployCommanderInterface $deployCommander
     * @return void
     */
    public function __construct(ValidableInterface $validator, ProjectInterface $project, DeployCommanderInterface $deployCommander)
    {
        $this->validator = $validator;
        $this->project = $project;
        $this->deployCommander = $deployCommander;
    }

    /**
     * Create a new deployment.
     *
     * @param array $input Data to create a deployment
     * @return boolean
     */
    public function save(array $input)
    {
        if (!$this->valid($input)) {
            return false;
        }

        $deployment = DB::transaction(function () use ($input) {
            /** @var $project Project */
            $project = $this->project->byId($input['project_id']);
            $maxDeployment = $project->getMaxDeployment();
            $input['number'] = $maxDeployment->number + 1;

            if (isset($input['actor']) && $input['actor'] === 'bitbucket') {
                $lastDeployment = $project->getLastDeployment();
                $lastDeploymentTime = strtotime($lastDeployment->getAttributes()['created_at']);
                $now = strtotime('now');

                if ($now - $lastDeploymentTime < 60) {
                    return 'double-call';
                }

                unset($input['actor']);
            }

            $project->addDeployment($input);
            $project->updateMaxDeployment(['number' => $input['number']]);
            $deployment = $project->getDeploymentByNumber($input['number']);
            return $deployment;
        });

        if ($deployment === 'double-call') {
            return true;
        }

        if (!$deployment) {
            return false;
        }

        if ($input["task"] !== "deployment") {
            $this->deployCommander->{$input['task']}($deployment);
        }

        return true;
    }

    /**
     * Test whether form validator passes.
     *
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }

    /**
     * Return validation errors.
     *
     * @return array
     */
    public function errors()
    {
        return $this->validator->errors();
    }
}
