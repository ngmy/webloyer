<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Spatie\ViewModels\ViewModel;

class CreateViewModel extends ViewModel
{
    private $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function roles(): array
    {
        return $this->roles;
    }
}
