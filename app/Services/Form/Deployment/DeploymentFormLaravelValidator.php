<?php
declare(strict_types=1);

namespace App\Services\Form\Deployment;

use App\Services\Validation\AbstractLaravelValidator;

/**
 * Class DeploymentFormLaravelValidator
 * @package App\Services\Form\Deployment
 */
class DeploymentFormLaravelValidator extends AbstractLaravelValidator
{
    protected array $rules = [
        'project_id' => 'required|exists:projects,id',
        'task' => 'required|in:deploy,rollback,unlock',
        'user_id' => 'required|exists:users,id',
    ];
}
