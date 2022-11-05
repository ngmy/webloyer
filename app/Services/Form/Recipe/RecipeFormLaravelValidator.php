<?php
declare(strict_types=1);

namespace App\Services\Form\Recipe;

use App\Services\Validation\AbstractLaravelValidator;

/**
 * Class RecipeFormLaravelValidator
 * @package App\Services\Form\Recipe
 */
class RecipeFormLaravelValidator extends AbstractLaravelValidator
{
    protected array $rules = [
        'name' => 'required',
        'body' => 'required',
    ];
}
