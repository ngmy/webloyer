<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project;

use Spatie\ViewModels\ViewModel;

class CreateViewModel extends ViewModel
{
    private $recipes;
    private $servers;
    private $users;

    public function __construct(
        array $recipes,
        array $servers,
        array $users
    ) {
        $this->recipes = $recipes;
        $this->servers = $servers;
        $this->users = $users;
    }

    public function recipes(): array
    {
        return array_column($this->recipes, 'name', 'id');
    }

    public function servers(): array
    {
        return array_column($this->servers, 'name', 'id');
    }

    public function users(): array
    {
        return ['' => ''] + array_column($this->users, 'email', 'id');
    }
}
