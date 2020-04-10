<?php

namespace App\Services\Form\Project;

use App\Entities\ProjectAttribute\ProjectAttributeEntity;
use App\Repositories\Project\ProjectInterface;
use App\Services\Validation\ValidableInterface;
use DB;

class ProjectForm
{
    protected $validator;

    protected $project;

    /**
     * Create a new form service instance.
     *
     * @param \App\Services\Validation\ValidableInterface $validator
     * @param \App\Repositories\Project\ProjectInterface  $project
     * @return void
     */
    public function __construct(ValidableInterface $validator, ProjectInterface $project)
    {
        $this->validator = $validator;
        $this->project   = $project;
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

        DB::transaction(function () use ($input) {
            $projectAttribute = new ProjectAttributeEntity();
            if (!empty($input['deploy_path'])) {
                $projectAttribute->setDeployPath($input['deploy_path']);
            }
            $input['attributes'] = $projectAttribute;

            if (isset($input['keep_last_deployment'])) {
                $input['keep_last_deployment'] = true;
            } else {
                $input['keep_last_deployment'] = false;
            }

            $project = $this->project->create($input);

            $project->addMaxDeployment();
            $project->syncRecipes($input['recipe_id']);
        });

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

        DB::transaction(function () use ($input) {
            $project = $this->project->byId($input['id']);

            $project->syncRecipes($input['recipe_id']);

            $projectAttribute = new ProjectAttributeEntity();
            if (!empty($input['deploy_path'])) {
                $projectAttribute->setDeployPath($input['deploy_path']);
            }
            $input['attributes'] = $projectAttribute;

            if (isset($input['keep_last_deployment'])) {
                $input['keep_last_deployment'] = true;
            } else {
                $input['keep_last_deployment'] = false;
            }

            $this->project->update($input);
        });

        return true;
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

    /**
     * Test whether form validator passes.
     *
     * @return boolean
     */
    protected function valid(array $input)
    {
        return $this->validator->with($input)->passes();
    }
}
