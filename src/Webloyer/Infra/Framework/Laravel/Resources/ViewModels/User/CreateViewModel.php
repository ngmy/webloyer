<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Spatie\ViewModels\ViewModel;
use Webloyer\Domain\Model\Role\RoleView;

class CreateViewModel extends ViewModel
{
    private $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function roles(): array
    {
        return array_reduce($this->roles, function (array $carry, RoleView $role): array {
            $carry[$role->name()] = $role->slug();
            return $carry;
        }, []);
    }
}
