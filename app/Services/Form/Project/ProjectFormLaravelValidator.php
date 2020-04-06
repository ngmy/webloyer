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
        'deploy_path'                       => 'nullable|string',
        'email_notification_recipient'      => 'nullable|email',
        'days_to_keep_deployments'          => 'nullable|integer|min:1',
        'max_number_of_deployments_to_keep' => 'nullable|integer|min:1',
        'keep_last_deployment'              => 'nullable|boolean',
        'github_webhook_secret'             => 'nullable|string',
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
