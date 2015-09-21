<?php

namespace App\Services\Form\User;

use App\Services\Validation\AbstractLaravelValidator;

class UserFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'name'     => 'sometimes|required',
        'email'    => 'sometimes|required|email',
        'password' => 'sometimes|required|min:8|confirmed',
        'role'     => 'sometimes|required',
    ];

    protected function rules()
    {
        $rules = [];

        if (isset($this->data['role'])) {
            foreach ($this->data['role'] as $key => $val) {
                $rules["role.$key"] = 'required|exists:roles,id';
            }
        }

        return $rules;
    }
}
