<?php

use Webloyer\Domain\Model\Deployment\Deployment;
use Webloyer\Domain\Model\Project\Project;
use Webloyer\Domain\Model\Recipe\Recipe;
use Webloyer\Domain\Model\Server\Server;
use Webloyer\Domain\Model\User\User;

Breadcrumbs::register('projects.index', function ($breadcrumbs) {
    $breadcrumbs->push('Projects', route('projects.index'));
});

Breadcrumbs::register('projects.create', function ($breadcrumbs) {
    $breadcrumbs->parent('projects.index');
    $breadcrumbs->push('Create', route('projects.create'));
});

Breadcrumbs::register('projects.show', function ($breadcrumbs, Project $project) {
    $breadcrumbs->parent('projects.index');
    $breadcrumbs->push($project->name(), route('projects.show', [$project->surrogateId()]));
});

Breadcrumbs::register('projects.edit', function ($breadcrumbs, Project $project) {
    $breadcrumbs->parent('projects.show', $project);
    $breadcrumbs->push('Edit', route('projects.edit', [$project->surrogateId()]));
});

Breadcrumbs::register('projects.deployments.index', function ($breadcrumbs, Project $project) {
    $breadcrumbs->parent('projects.show', $project);
    $breadcrumbs->push('Deployments', route('projects.deployments.index', [$project->surrogateId()]));
});

Breadcrumbs::register('projects.deployments.show', function ($breadcrumbs, Project $project, Deployment $deployment) {
    $breadcrumbs->parent('projects.deployments.index', $project);
    $breadcrumbs->push($deployment->number(), route('projects.deployments.show', [$project->surrogateId(), $deployment->number()]));
});

Breadcrumbs::register('recipes.index', function ($breadcrumbs) {
    $breadcrumbs->push('Recipes', route('recipes.index'));
});

Breadcrumbs::register('recipes.create', function ($breadcrumbs) {
    $breadcrumbs->parent('recipes.index');
    $breadcrumbs->push('Create', route('recipes.create'));
});

Breadcrumbs::register('recipes.show', function ($breadcrumbs, Recipe $recipe) {
    $breadcrumbs->parent('recipes.index');
    $breadcrumbs->push($recipe->name(), route('recipes.show', [$recipe->surrogateId()]));
});

Breadcrumbs::register('recipes.edit', function ($breadcrumbs, Recipe $recipe) {
    $breadcrumbs->parent('recipes.show', $recipe);
    $breadcrumbs->push('Edit', route('recipes.edit', [$recipe->surrogateId()]));
});

Breadcrumbs::register('servers.index', function ($breadcrumbs) {
    $breadcrumbs->push('Servers', route('servers.index'));
});

Breadcrumbs::register('servers.create', function ($breadcrumbs) {
    $breadcrumbs->parent('servers.index');
    $breadcrumbs->push('Create', route('servers.create'));
});

Breadcrumbs::register('servers.show', function ($breadcrumbs, Server $server) {
    $breadcrumbs->parent('servers.index');
    $breadcrumbs->push($server->name(), route('servers.show', [$server->surrogateId()]));
});

Breadcrumbs::register('servers.edit', function ($breadcrumbs, Server $server) {
    $breadcrumbs->parent('servers.show', $server);
    $breadcrumbs->push('Edit', route('servers.edit', [$server->surrogateId()]));
});

Breadcrumbs::register('users.index', function ($breadcrumbs) {
    $breadcrumbs->push('Users', route('users.index'));
});

Breadcrumbs::register('users.create', function ($breadcrumbs) {
    $breadcrumbs->parent('users.index');
    $breadcrumbs->push('Create', route('users.create'));
});

Breadcrumbs::register('users.show', function ($breadcrumbs, User $user) {
    $breadcrumbs->parent('users.index');
    $breadcrumbs->push($user->name(), route('users.show', [$user->surrogateId()]));
});

Breadcrumbs::register('users.edit', function ($breadcrumbs, User $user) {
    $breadcrumbs->parent('users.show', $user);
    $breadcrumbs->push('Edit', route('users.edit', [$user->surrogateId()]));
});

Breadcrumbs::register('users.password.change', function ($breadcrumbs, User $user) {
    $breadcrumbs->parent('users.show', $user);
    $breadcrumbs->push('Change Password', route('users.password.change', [$user->surrogateId()]));
});

Breadcrumbs::register('users.role.edit', function ($breadcrumbs, User $user) {
    $breadcrumbs->parent('users.show', $user);
    $breadcrumbs->push('Edit Role', route('users.role.edit', [$user->surrogateId()]));
});
