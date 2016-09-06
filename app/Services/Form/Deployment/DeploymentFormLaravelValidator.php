<?php

namespace App\Services\Form\Deployment;

use App\Services\Validation\AbstractLaravelValidator;

class DeploymentFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'project_id' => 'required|exists:projects,id',
        'task'       => 'required|in:deploy,rollback',
        'user_id'    => 'required|exists:users,id',
    ];
}
