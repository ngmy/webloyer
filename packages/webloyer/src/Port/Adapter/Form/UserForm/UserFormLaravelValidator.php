<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm;

use Ngmy\Webloyer\Common\Port\Adapter\Validation\AbstractLaravelValidator;

class UserFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'name'     => 'sometimes|required',
        'password' => 'sometimes|required|min:8|confirmed',
        'role'     => 'sometimes|required',
    ];

    /**
     * Return validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        $rules = [];

        // For role
        if (isset($this->data['role'])) {
            foreach ($this->data['role'] as $key => $val) {
                $rules["role.$key"] = 'required|exists:roles,id';
            }
        }

        // For email
        $unique = 'unique:users,email';

        if (isset($this->data['id'])) {
            $unique .= ','.$this->data['id'];
        }

        $rules['email'] = "sometimes|required|email|$unique";

        return $rules;
    }
}
