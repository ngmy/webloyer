@extends('app')

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
                    @foreach ($projectRecipe as $i => $recipe)
                        <tr>
                            @if ($i === 0)
                                <th rowspan="{{ count($projectRecipe) }}">Recipe</th>
                            @endif
                            <td>{{ $recipe['name'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>Server</th>
                        <td>{{ $projectServer->name }}</td>
                    </tr>
                    <tr>
                        <th>Repository URL</th>
                        <td>{{ $project->repository }}</td>
                    </tr>
                    <tr>
                        <th>Stage</th>
                        <td>{{ $project->stage }}</td>
                    </tr>
                    <tr>
                        <th>Deploy Path</th>
                        <td>{{ $project->attributes->getDeployPath() }}</td>
                    </tr>
                    <tr>
                        <th>E-Mail Notification Recipient</th>
                        <td>{{ $project->email_notification_recipient }}</td>
                    </tr>
                    <tr>
                        <th>Days To Keep Deployments</th>
                        <td>{{ $project->days_to_keep_deployments }}</td>
                    </tr>
                    <tr>
                        <th>Keep Last Deployment</th>
                        <td>{{ $project->keep_last_deployment }}</td>
                    </tr>
                    <tr>
                        <th>Max # Of Deployments To Keep</th>
                        <td>{{ $project->max_number_of_deployments_to_keep }}</td>
                    </tr>
                    <tr>
                        <th>GitHub Webhook Secret</th>
                        <td>{{ $project->github_webhook_secret }}</td>
                    </tr>
                    <tr>
                        <th>GitHub Webhook Execute By</th>
                        <td>{{ is_null($project->getGithubWebhookUser()) ? '' : $project->getGithubWebhookUser()->email }}</td>
                    </tr>
                    <tr>
                        <th>Bitbucket Webhook Secret</th>
                        <td>{{ $project->bitbucket_webhook_secret }}</td>
                    </tr>
                    <tr>
                        <th>Bitbucket Webhook Execute By</th>
                        <td>{{ is_null($project->getBitbucketWebhookUser()) ? '' : $project->getBitbucketWebhookUser()->email }}</td>
                    </tr>
                </tbody>
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
