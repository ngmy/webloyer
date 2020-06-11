<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Spatie\ViewModels\ViewModel;

class EditRoleViewModel extends ViewModel
{
    private $user;
    private $roles;

    public function __construct(object $user, array $roles)
    {
        $this->user = $user;
        $this->roles = $roles;
    }

    public function user(): object
    {
        return $this->user;
    }

    public function roles(): array
    {
        return $this->roles;
    }
}
