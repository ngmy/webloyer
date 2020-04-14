<?php

namespace App\Services\Form\Project;

use App\Entities\ProjectAttribute\ProjectAttributeEntity;
use App\Repositories\Project\ProjectInterface;
use DB;

class ProjectForm
{
    protected $project;

    /**
     * Create a new form service instance.
     *
     * @param \App\Repositories\Project\ProjectInterface  $project
     * @return void
     */
    public function __construct(ProjectInterface $project)
    {
        $this->project = $project;
    }

    /**
     * Create a new project.
     *
     * @param array $input Data to create a project
     * @return bool
     */
    public function save(array $input)
    {
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
     * @return bool
     */
    public function update(array $input)
    {
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
}
