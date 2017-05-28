<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm;

use Ngmy\Webloyer\Common\Port\Adapter\Validation\AbstractLaravelValidator;

class DeploymentFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'project_id' => 'required|exists:projects,id',
        'task'       => 'required|in:deploy,rollback',
        'user_id'    => 'required|exists:users,id',
    ];
}
