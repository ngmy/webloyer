<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Spatie\ViewModels\ViewModel;
use Webloyer\Domain\Model\Role\RoleView;

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
        return array_reduce($this->roles, function (array $carry, RoleView $role): array {
            $carry[$role->name()] = $role->slug();
            return $carry;
        }, []);
    }
}
