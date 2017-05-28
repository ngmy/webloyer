<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm;

use Ngmy\Webloyer\Common\Port\Adapter\Validation\AbstractLaravelValidator;

class RecipeFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'name' => 'required',
        'body' => 'required',
    ];
}
