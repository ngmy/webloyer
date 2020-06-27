<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Resources\ViewModels\Project;

use Spatie\ViewModels\ViewModel;

class EditViewModel extends ViewModel
{
    /** @var object */
    private $project;
    /** @var list<object> */
    private $recipes;
    /** @var list<object> */
    private $servers;
    /** @var list<object> */
    private $users;

    /**
     * @param object       $project
     * @param list<object> $recipes
     * @param list<object> $servers
     * @param list<object> $users
     * @return void
     */
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

    /**
     * @return object
     */
    public function project(): object
    {
        return $this->project;
    }

    /**
     * @return array<string, string>
     */
    public function recipeSelectBoxOptions(): array
    {
        return array_column($this->recipes, 'name', 'id');
    }

    /**
     * @return array<string, string>
     */
    public function serverSelectBoxOptions(): array
    {
        return array_column($this->servers, 'name', 'id');
    }

    /**
     * @return array<string, string>
     */
    public function userSelectBoxOptions(): array
    {
        return ['' => ''] + array_column($this->users, 'email', 'id');
    }
}
