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

    private static $slugs = [
        self::ADMINISTRATOR => 'administrator',
        self::DEVELOPER => 'developer',
        self::OPERATOR => 'operator',
    ];

    private static $names = [
        self::ADMINISTRATOR => 'Administrator',
        self::DEVELOPER => 'Developer',
        self::OPERATOR => 'Operator',
    ];

    public static function slugs(): array
    {
        return array_combine(self::$names, self::$slugs);
    }

    public function create(): UserRole
    {
        $class = $this->value();
        return new $class();
    }

    public function slug(): string
    {
        return self::$slugs[$this->value()];
    }
}
