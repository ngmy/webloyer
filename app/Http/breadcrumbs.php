<?php

Breadcrumbs::register('projects.index', function ($breadcrumbs) {
    $breadcrumbs->push('Projects', route('projects.index'));
});

Breadcrumbs::register('projects.create', function ($breadcrumbs) {
    $breadcrumbs->parent('projects.index');
    $breadcrumbs->push('Create', route('projects.create'));
});

Breadcrumbs::register('projects.show', function ($breadcrumbs, Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project) {
    $breadcrumbs->parent('projects.index');
    $breadcrumbs->push($project->name(), route('projects.show', [$project->projectId()->id()]));
});

Breadcrumbs::register('projects.edit', function ($breadcrumbs, Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project) {
    $breadcrumbs->parent('projects.show', $project);
    $breadcrumbs->push('Edit', route('projects.edit', [$project->projectId()->id()]));
});

Breadcrumbs::register('projects.deployments.index', function ($breadcrumbs, Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project) {
    $breadcrumbs->parent('projects.show', $project);
    $breadcrumbs->push('Deployments', route('projects.deployments.index', [$project->projectId()->id()]));
});

Breadcrumbs::register('projects.deployments.show', function ($breadcrumbs, Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project, Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment $deployment) {
    $breadcrumbs->parent('projects.deployments.index', $project);
    $breadcrumbs->push($deployment->deploymentId()->id(), route('projects.deployments.show', [$project->projectId()->id(), $deployment->deploymentId()->id()]));
});

Breadcrumbs::register('recipes.index', function ($breadcrumbs) {
    $breadcrumbs->push('Recipes', route('recipes.index'));
});

Breadcrumbs::register('recipes.create', function ($breadcrumbs) {
    $breadcrumbs->parent('recipes.index');
    $breadcrumbs->push('Create', route('recipes.create'));
});

Breadcrumbs::register('recipes.show', function ($breadcrumbs, Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe $recipe) {
    $breadcrumbs->parent('recipes.index');
    $breadcrumbs->push($recipe->name(), route('recipes.show', [$recipe->recipeId()->id()]));
});

Breadcrumbs::register('recipes.edit', function ($breadcrumbs, Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe $recipe) {
    $breadcrumbs->parent('recipes.show', $recipe);
    $breadcrumbs->push('Edit', route('recipes.edit', [$recipe->recipeId()->id()]));
});

Breadcrumbs::register('servers.index', function ($breadcrumbs) {
    $breadcrumbs->push('Servers', route('servers.index'));
});

Breadcrumbs::register('servers.create', function ($breadcrumbs) {
    $breadcrumbs->parent('servers.index');
    $breadcrumbs->push('Create', route('servers.create'));
});

Breadcrumbs::register('servers.show', function ($breadcrumbs, Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server $server) {
    $breadcrumbs->parent('servers.index');
    $breadcrumbs->push($server->name(), route('servers.show', [$server->serverId()->id()]));
});

Breadcrumbs::register('servers.edit', function ($breadcrumbs, Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server $server) {
    $breadcrumbs->parent('servers.show', $server);
    $breadcrumbs->push('Edit', route('servers.edit', [$server->serverId()->id()]));
});

Breadcrumbs::register('users.index', function ($breadcrumbs) {
    $breadcrumbs->push('Users', route('users.index'));
});

Breadcrumbs::register('users.create', function ($breadcrumbs) {
    $breadcrumbs->parent('users.index');
    $breadcrumbs->push('Create', route('users.create'));
});

Breadcrumbs::register('users.show', function ($breadcrumbs, Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user) {
    $breadcrumbs->parent('users.index');
    $breadcrumbs->push($user->name(), route('users.show', [$user->userId()->id()]));
});

Breadcrumbs::register('users.edit', function ($breadcrumbs, Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user) {
    $breadcrumbs->parent('users.show', $user);
    $breadcrumbs->push('Edit', route('users.edit', [$user->userId()->id()]));
});

Breadcrumbs::register('users.password.change', function ($breadcrumbs, Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user) {
    $breadcrumbs->parent('users.show', $user);
    $breadcrumbs->push('Change Password', route('users.password.change', [$user->userId()->id()]));
});

Breadcrumbs::register('users.role.edit', function ($breadcrumbs, Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user) {
    $breadcrumbs->parent('users.show', $user);
    $breadcrumbs->push('Edit Role', route('users.role.edit', [$user->userId()->id()]));
});
