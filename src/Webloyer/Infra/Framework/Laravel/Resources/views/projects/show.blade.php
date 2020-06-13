@extends('webloyer::app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table">
                <tbody>
                    <tr>
                        <th>Project Name</th>
                        <td>{{ $project->name }}</td>
                    </tr>
                    @foreach ($projectRecipes as $recipe)
                        <tr>
                            @if ($loop->first)
                                <th rowspan="{{ $projectRecipeCount }}">Recipe</th>
                            @endif
                            <td>{{ $hyphenIfBlank($recipe->name) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>Server</th>
                        <td>{{ $hyphenIfBlank($project->server ? $project->server->name : '') }}</td>
                    </tr>
                    <tr>
                        <th>Repository URL</th>
                        <td>{{ $project->repositoryUrl }}</td>
                    </tr>
                    <tr>
                        <th>Stage</th>
                        <td>{{ $project->stageName }}</td>
                    </tr>
                    <tr>
                        <th>Deploy Path</th>
                        <td>{{ $hyphenIfBlank($project->deployPath) }}</td>
                    </tr>
                    <tr>
                        <th>E-Mail Notification Recipient</th>
                        <td>{{ $hyphenIfBlank($project->emailNotificationRecipient) }}</td>
                    </tr>
                    <tr>
                        <th>Days To Keep Deployments</th>
                        <td>{{ $hyphenIfBlank($project->deploymentKeepDays) }}</td>
                    </tr>
                    <tr>
                        <th>Keep Last Deployment</th>
                        <td>{{ $yesOrNo($project->keepLastDeployment) }}</td>
                    </tr>
                    <tr>
                        <th>Max # Of Deployments To Keep</th>
                        <td>{{ $hyphenIfBlank($project->deploymentKeepMaxNumber) }}</td>
                    </tr>
                    <tr>
                        <th>GitHub Webhook Secret</th>
                        <td>{{ $hyphenIfBlank($project->gitHubWebhookSecret) }}</td>
                    </tr>
                    <tr>
                        <th>GitHub Webhook Execute By</th>
                        <td>{{ $hyphenIfBlank($projectGitHubWebhookUserEmail($project)) }}</td>
                    </tr>
                </tbody>
            </table>
            {!! link_to_route('projects.index', 'Back', [], ['class' => 'btn btn-danger']) !!}
            @if (Auth::user()->hasPermission('update.project'))
                {!! link_to_route('projects.edit', 'Edit', [$project->id], ['class' => 'btn btn-primary']) !!}
            @endif
        </div>
    </div>
</div>
@stop
