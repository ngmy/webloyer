<?php

namespace App\Services\Form\Server;

use App\Services\Validation\AbstractLaravelValidator;

class ServerFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'name' => 'required',
        'body' => 'required',
    ];
}
