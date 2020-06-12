<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project;

use Spatie\ViewModels\ViewModel;

class CreateViewModel extends ViewModel
{
    /** @var array<int, object> */
    private $recipes;
    /** @var array<int, object> */
    private $servers;
    /** @var array<int, object> */
    private $users;

    /**
     * @param array<int, object> $recipes
     * @param array<int, object> $servers
     * @param array<int, object> $users
     * @return void
     */
    public function __construct(
        array $recipes,
        array $servers,
        array $users
    ) {
        $this->recipes = $recipes;
        $this->servers = $servers;
        $this->users = $users;
    }

    /**
     * @return array<string, string>
     */
    public function recipes(): array
    {
        return array_column($this->recipes, 'name', 'id');
    }

    /**
     * @return array<string, string>
     */
    public function servers(): array
    {
        return array_column($this->servers, 'name', 'id');
    }

    /**
     * @return array<string, string>
     */
    public function users(): array
    {
        return ['' => ''] + array_column($this->users, 'email', 'id');
    }
}
