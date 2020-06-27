<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User;

use Spatie\ViewModels\ViewModel;
use Webloyer\Domain\Model\Role\RoleView;

class CreateViewModel extends ViewModel
{
    /** @var list<RoleView> */
    private $roles;

    /**
     * @param list<RoleView> $roles
     * @return void
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array<string, string>
     */
    public function roleCheckBoxLabelToValue(): array
    {
        return array_reduce($this->roles, function (array $carry, RoleView $role): array {
            $carry[$role->name()] = $role->slug();
            return $carry;
        }, []);
    }
}
