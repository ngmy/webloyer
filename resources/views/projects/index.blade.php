@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1 class="page-header">Projects</h1>

            @if (Auth::user()->can('create.project'))
                <div class="pull-right margin-bottom-lg">
                    {!! link_to_route('projects.create', 'Create', [], ['class' => 'btn btn-primary btn-lg']) !!}
                </div>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><div align="center"></div></th>
                        <th><div align="center">Name</div></th>
                        <th><div align="center">Last Deployment</div></th>
                        <th><div align="center">Created At</div></th>
                        <th><div align="center">Updated At</div></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $project)
                        <tr>
                            <td>
                            @if (!empty($lastDeployments[$project->projectId()->id()]))
                                    {!! $lastDeployments[$project->projectId()->id()]->statusIcon() !!}
                                @endif
                            </td>
                            <td>{{ $project->name() }}</td>
                            <td>
                                @if (!empty($lastDeployments[$project->projectId()->id()]))
                                    {{ $lastDeployments[$project->projectId()->id()]->updatedAt() }}
                                    ({!! link_to_route('projects.deployments.show', "#{$lastDeployments[$project->projectId()->id()]->deploymentId()->id()}", [$project->projectId()->id(),  $lastDeployments[$project->projectId()->id()]->deploymentId()->id()]) !!})
                                @endif
                            </td>
                            <td>{{ $project->createdAt() }}</td>
                            <td>{{ $project->updatedAt() }}</td>
                            <td>
                                {!! link_to_route('projects.deployments.index', 'Deployments', [$project->projectId()->id()], ['class' => 'btn btn-default']) !!}
                                {!! link_to_route('projects.show', 'Show', [$project->projectId()->id()], ['class' => 'btn btn-default']) !!}
                                @if (Auth::user()->can('edit.project'))
                                    {!! link_to_route('projects.edit', 'Edit', [$project->projectId()->id()], ['class' => 'btn btn-default']) !!}
                                @endif
                                @if (Auth::user()->can('delete.project'))
                                    {!! Form::open(['route' => ['projects.destroy', $project->projectId()->id()], 'method' => 'delete', 'style' => 'display:inline']) !!}
                                    {!! Form::submit('Destroy', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {!! $projects->render() !!}
            </div>
        </div>
    </div>
</div>
@stop
