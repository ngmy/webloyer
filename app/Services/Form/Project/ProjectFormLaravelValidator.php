<?php

namespace App\Services\Form\Project;

use App\Services\Validation\AbstractLaravelValidator;

class ProjectFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'name'                         => 'required',
        'stage'                        => 'required',
        'recipe_id'                    => 'required',
        'server_id'                    => 'required|exists:servers,id',
        'repository'                   => 'required|url',
        'email_notification_recipient' => 'email',
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
