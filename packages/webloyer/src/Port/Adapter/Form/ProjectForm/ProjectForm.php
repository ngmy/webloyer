<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ProjectForm;

use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\KeepLastDeployment;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;

class ProjectForm
{
    protected $validator;

    protected $projectService;

    /**
     * Create a new form service instance.
     *
     * @param \Ngmy\Webloyer\Common\Validation\ValidableInterface        $validator
     * @param \Ngmy\Webloyer\Webloyer\Application\Project\ProjectService $projectService
     * @return void
     */
    public function __construct(ValidableInterface $validator, ProjectService $projectService)
    {
        $this->validator = $validator;
        $this->projectService = $projectService;
    }

    /**
     * Create a new project.
     *
     * @param array $input Data to create a project
     * @return boolean
     */
    public function save(array $input)
    {
        $input['recipe_id'] = explode(',', $input['recipe_id_order']);

        if (!$this->valid($input)) {
            return false;
        }

        if (isset($input['keep_last_deployment'])) {
            $keepLastDeployment = KeepLastDeployment::ENUM['on'];
        } else {
            $keepLastDeployment = KeepLastDeployment::ENUM['off'];
        }

        $this->projectService->saveProject(
            null,
            $input['name'],
            $input['recipe_id'],
            $input['server_id'],
            $input['repository'],
            $input['stage'],
            $input['deploy_path'],
            $input['email_notification_recipient'],
            $input['days_to_keep_deployments'],
            $input['max_number_of_deployments_to_keep'],
            (int) $keepLastDeployment,
            $input['github_webhook_secret'],
            $input['github_webhook_user_id'],
            null
        );

        return true;
    }

    /**
     * Update an existing project.
     *
     * @param array $input Data to update a project
     * @return boolean
     */
    public function update(array $input)
    {
        $input['recipe_id'] = explode(',', $input['recipe_id_order']);

        if (!$this->valid($input)) {
            return false;
        }

        if (isset($input['keep_last_deployment'])) {
            $keepLastDeployment = KeepLastDeployment::ENUM['on'];
        } else {
            $keepLastDeployment = KeepLastDeployment::ENUM['off'];
        }

        $this->projectService->saveProject(
            $input['id'],
            $input['name'],
            $input['recipe_id'],
            $input['server_id'],
            $input['repository'],
            $input['stage'],
            $input['deploy_path'],
            $input['email_notification_recipient'],
            $input['days_to_keep_deployments'],
            $input['max_number_of_deployments_to_keep'],
            (int) $keepLastDeployment,
            $input['github_webhook_secret'],
            $input['github_webhook_user_id'],
            $input['concurrency_version']
        );

        return true;
    }

    /**
     * Return validation errors.
     *
     * @return \Illuminate\Contracts\Support\MessageBag
     */
    public function errors()
    {
        return $this->validator->errors();
    }

    /**
     * Test whether form validator passes.
     *
     * @param array $input Data to test whether form validator passes
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }
}
