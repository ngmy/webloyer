<?php
declare(strict_types=1);

namespace App\Services\Form\Server;

use App\Services\Validation\AbstractLaravelValidator;

/**
 * Class ServerFormLaravelValidator
 * @package App\Services\Form\Server
 */
class ServerFormLaravelValidator extends AbstractLaravelValidator
{
    protected array $rules = [
        'name' => 'required',
        'body' => 'required',
    ];
}
