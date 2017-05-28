<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ServerForm;

use Ngmy\Webloyer\Common\Port\Adapter\Validation\AbstractLaravelValidator;

class ServerFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'name' => 'required',
        'body' => 'required',
    ];
}
