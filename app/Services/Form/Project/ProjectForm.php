<?php

namespace App\Services\Form\Project;

use App\Services\Validation\ValidableInterface;
use App\Repositories\Project\ProjectInterface;

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

        return $this->project->create($input);
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

        return $this->project->update($input);
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
