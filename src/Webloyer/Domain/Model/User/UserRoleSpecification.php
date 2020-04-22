<?php

declare(strict_types=1);

namespace Webloyer\Domain\Model\User;

use Common\Enumerable;

/**
 * @method static self administrator()
 * @method static self developer()
 * @method static self operator()
 */
class UserRoleSpecification
{
    use Enumerable;

    /** @var string */
    private const ADMINISTRATOR = Roles\AdministratorRole::class;
    /** @var string */
    private const DEVELOPER = Roles\DeveloperRole::class;
    /** @var string */
    private const OPERATOR = Roles\OperatorRole::class;

    public function create(): UserRole
    {
        $class = $this->value();
        return new $class();
    }
}
