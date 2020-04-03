<?php

namespace App\Services\Form\Recipe;

use App\Services\Validation\AbstractLaravelValidator;

class RecipeFormLaravelValidator extends AbstractLaravelValidator
{
    protected $rules = [
        'name' => 'required',
        'body' => 'required',
    ];
}
