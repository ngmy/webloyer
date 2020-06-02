<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project;

use Spatie\ViewModels\ViewModel;

class EditViewModel extends ViewModel
{
    private $project;
    private $recipes;
    private $servers;
    private $users;

    public function __construct(
        object $project,
        array $recipes,
        array $servers,
        array $users
    ) {
        $this->project = $project;
        $this->recipes = $recipes;
        $this->servers = $servers;
        $this->users = $users;
    }

    public function project(): object
    {
        return $this->project;
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
