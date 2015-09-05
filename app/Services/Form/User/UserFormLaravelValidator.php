<?php

namespace App\Services\Form\User;

use App\Services\Validation\AbstractLaravelValidator;

class UserFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'name'     => 'sometimes|required',
        'email'    => 'sometimes|required|email',
        'password' => 'sometimes|required|min:8|confirmed',
    ];
}
