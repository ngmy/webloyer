<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Spatie\ViewModels\ViewModel;
use Webloyer\Domain\Model\Role\RoleView;

class EditRoleViewModel extends ViewModel
{
    /** @var object */
    private $user;
    /** @var array<int, RoleView> */
    private $roles;

    /**
     * @param object               $user
     * @param array<int, RoleView> $roles
     * @return void
     */
    public function __construct(object $user, array $roles)
    {
        $this->user = $user;
        $this->roles = $roles;
    }

    /**
     * @return object
     */
    public function user(): object
    {
        return $this->user;
    }

    /**
     * @return array<string, string>
     */
    public function roles(): array
    {
        return array_reduce($this->roles, function (array $carry, RoleView $role): array {
            $carry[$role->name()] = $role->slug();
            return $carry;
        }, []);
    }
}
