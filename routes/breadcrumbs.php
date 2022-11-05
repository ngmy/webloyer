<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('index', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('index'));
});

Breadcrumbs::for('projects.index', function (BreadcrumbTrail $trail) {
    $trail->push('Projects', route('projects.index'));
});

Breadcrumbs::for('projects.create', function (BreadcrumbTrail $trail) {
    $trail->push('Create', route('projects.create'));
});

Breadcrumbs::for('projects.show', function (BreadcrumbTrail $trail, App\Models\Project $project) {
    $trail->parent('projects.index');
    $trail->push($project->name, route('projects.show', $project));
});

Breadcrumbs::for('projects.edit', function (BreadcrumbTrail $trail, App\Models\Project $project) {
    $trail->parent('projects.index');
    $trail->push('Edit', route('projects.edit', $project));
});

Breadcrumbs::for('projects.deployments.index', function (BreadcrumbTrail $trail, App\Models\Project $project) {
    $trail->parent('projects.index');
    $trail->push('Deployments', route('projects.deployments.index', $project));
});

Breadcrumbs::for('projects.deployments.show', function (BreadcrumbTrail $trail, App\Models\Project $project, App\Models\Deployment $deployment) {
    $trail->parent('projects.deployments.index', $project);
    $trail->push($deployment->number, route('projects.deployments.show', [$project, $deployment]));
});

Breadcrumbs::for('recipes.index', function (BreadcrumbTrail $trail) {
    $trail->push('Recipes', route('recipes.index'));
});

Breadcrumbs::for('recipes.create', function (BreadcrumbTrail $trail) {
    $trail->parent('recipes.index');
    $trail->push('Create', route('recipes.create'));
});

Breadcrumbs::for('recipes.show', function (BreadcrumbTrail $trail, App\Models\Recipe $recipe) {
    $trail->parent('recipes.index');
    $trail->push($recipe->name, route('recipes.show', $recipe));
});

Breadcrumbs::for('recipes.edit', function (BreadcrumbTrail $trail, App\Models\Recipe $recipe) {
    $trail->parent('recipes.index');
    $trail->push('Edit', route('recipes.edit', $recipe));
});

Breadcrumbs::for('servers.index', function (BreadcrumbTrail $trail) {
    $trail->push('Servers', route('servers.index'));
});

Breadcrumbs::for('servers.create', function (BreadcrumbTrail $trail) {
    $trail->parent('servers.index');
    $trail->push('Create', route('servers.create'));
});

Breadcrumbs::for('servers.show', function (BreadcrumbTrail $trail, App\Models\Server $server) {
    $trail->parent('servers.index');
    $trail->push($server->name, route('servers.show', $server));
});

Breadcrumbs::for('servers.edit', function (BreadcrumbTrail $trail, App\Models\Server $server) {
    $trail->parent('servers.index');
    $trail->push('Edit', route('servers.edit', $server));
});

Breadcrumbs::for('users.index', function (BreadcrumbTrail $trail) {
    $trail->push('Users', route('users.index'));
});

Breadcrumbs::for('users.create', function (BreadcrumbTrail $trail) {
    $trail->parent('users.index');
    $trail->push('Create', route('users.create'));
});

Breadcrumbs::for('users.show', function (BreadcrumbTrail $trail, App\Models\User $user) {
    $trail->parent('users.index');
    $trail->push($user->name, route('users.show', $user));
});

Breadcrumbs::for('users.edit', function (BreadcrumbTrail $trail, App\Models\User $user) {
    $trail->parent('users.index');
    $trail->push('Edit', route('users.edit', $user));
});

Breadcrumbs::for('users.api_token.edit', function (BreadcrumbTrail $trail, App\Models\User $user) {
    $trail->parent('users.index');
    $trail->push('Edit Token', route('users.api_token.edit', $user));
});

Breadcrumbs::for('users.password.change', function (BreadcrumbTrail $trail, App\Models\User $user) {
    $trail->parent('users.index');
    $trail->push('Change password', route('users.password.change', $user));
});

Breadcrumbs::for('users.role.edit', function (BreadcrumbTrail $trail, App\Models\User $user) {
    $trail->parent('users.index');
    $trail->push('Edit Role', route('users.role.edit', $user));
});

Breadcrumbs::for('settings.email', function (BreadcrumbTrail $trail) {
    $trail->push('Settings', route('settings.email'));
});
