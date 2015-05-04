<?php

Breadcrumbs::register('projects.index', function ($breadcrumbs)
{
	$breadcrumbs->push('Projects', route('projects.index'));
});

Breadcrumbs::register('projects.create', function ($breadcrumbs)
{
	$breadcrumbs->parent('projects.index');
	$breadcrumbs->push('Create', route('projects.create'));
});

Breadcrumbs::register('projects.show', function ($breadcrumbs, App\Models\Project $project)
{
	$breadcrumbs->parent('projects.index');
	$breadcrumbs->push($project->name, route('projects.show', $project));
});

Breadcrumbs::register('projects.edit', function ($breadcrumbs, App\Models\Project $project)
{
	$breadcrumbs->parent('projects.show', $project);
	$breadcrumbs->push('Edit', route('projects.edit', $project));
});

Breadcrumbs::register('projects.deployments.index', function ($breadcrumbs, App\Models\Project $project)
{
	$breadcrumbs->parent('projects.show', $project);
	$breadcrumbs->push('Deployments', route('projects.deployments.index', $project));
});

Breadcrumbs::register('projects.deployments.show', function ($breadcrumbs, App\Models\Project $project, App\Models\Deployment $deployment)
{
	$breadcrumbs->parent('projects.deployments.index', $project);
	$breadcrumbs->push($deployment->number, route('projects.deployments.show', $project, $deployment));
});
