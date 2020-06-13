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

    /** @var array<string, string> */
    private static $slugs = [
        self::ADMINISTRATOR => 'administrator',
        self::DEVELOPER => 'developer',
        self::OPERATOR => 'operator',
    ];

    /** @var array<string, string> */
    private static $names = [
        self::ADMINISTRATOR => 'Administrator',
        self::DEVELOPER => 'Developer',
        self::OPERATOR => 'Operator',
    ];

    /**
     * @return array<string, string>
     */
    public static function slugs(): array
    {
        assert(array_combine(self::$names, self::$slugs) !== false);
        return array_combine(self::$names, self::$slugs);
    }

    /**
     * @return UserRole
     */
    public function create(): UserRole
    {
        $class = $this->value();
        return new $class();
    }

    /**
     * @return string
     */
    public function slug(): string
    {
        return self::$slugs[$this->value()];
    }
}
