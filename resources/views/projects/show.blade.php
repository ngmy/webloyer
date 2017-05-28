@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table">
                <tbody>
                    <tr>
                        <th>Project Name</th>
                        <td>{{ $project->name() }}</td>
                    </tr>
                    @foreach ($projectRecipe as $i => $recipe)
                        <tr>
                            @if ($i === 0)
                                <th rowspan="{{ count($projectRecipe) }}">Recipe</th>
                            @endif
                            <td>{{ $recipe->name() }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>Server</th>
                        <td>{{ $projectServer->name() }}</td>
                    </tr>
                    <tr>
                        <th>Repository URL</th>
                        <td>{{ $project->repositoryUrl() }}</td>
                    </tr>
                    <tr>
                        <th>Stage</th>
                        <td>{{ $project->stage() }}</td>
                    </tr>
                    <tr>
                        <th>Deploy Path</th>
                        <td>{{ $project->attribute()->deployPath() }}</td>
                    </tr>
                    <tr>
                        <th>E-Mail Notification Recipient</th>
                        <td>{{ $project->emailNotificationRecipient() }}</td>
                    </tr>
                    <tr>
                        <th>Days To Keep Deployments</th>
                        <td>{{ $project->daysToKeepDeployments() }}</td>
                    </tr>
                    <tr>
                        <th>Keep Last Deployment</th>
                        <td>{{ $project->keepLastDeployment()->displayName() }}</td>
                    </tr>
                    <tr>
                        <th>Max # Of Deployments To Keep</th>
                        <td>{{ $project->maxNumberOfDeploymentsToKeep() }}</td>
                    </tr>
                    <tr>
                        <th>GitHub Webhook Secret</th>
                        <td>{{ $project->githubWebhookSecret() }}</td>
                    </tr>
                    <tr>
                        <th>GitHub Webhook Execute By</th>
                        <td>{{ is_null($project->githubWebhookExecuteUserId()->id()) ? '' : $project->githubWebhookExecuteUserId()->id() }}</td>
                    </tr>
                </tbody>
            </table>
            {!! link_to_route('projects.index', 'Back', [], ['class' => 'btn btn-danger']) !!}
            @if (Auth::user()->can('update.project'))
                {!! link_to_route('projects.edit', 'Edit', [$project->projectId()->id()], ['class' => 'btn btn-primary']) !!}
            @endif
        </div>
    </div>
</div>
@stop
