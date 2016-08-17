<?php

namespace App\Services\Form\Project;

use App\Services\Validation\AbstractLaravelValidator;

class ProjectFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'name'                              => 'required',
        'stage'                             => 'required',
        'recipe_id'                         => 'required',
        'server_id'                         => 'required|exists:servers,id',
        'repository'                        => 'required|url',
        'deploy_path'                       => 'string',
        'email_notification_recipient'      => 'email',
        'days_to_keep_deployments'          => 'integer|min:1',
        'max_number_of_deployments_to_keep' => 'integer|min:1',
        'keep_last_deployment'              => 'boolean',
    ];

    protected function rules()
    {
        $rules = [];

        if (isset($this->data['recipe_id'])) {
            foreach ($this->data['recipe_id'] as $key => $val) {
                $rules["recipe_id.$key"] = 'required|exists:recipes,id';
            }
        }

        return $rules;
    }
}
