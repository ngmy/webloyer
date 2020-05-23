@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if (Session::has('status'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {!! Session::get('status') !!}
                </div>
            @endif

            <h1 class="page-header">Deployments</h1>

            <div class="pull-right margin-bottom-lg">
                {!! Form::open(['route' => ['projects.deployments.store', $project], 'method' => 'post', 'style' => 'display:inline']) !!}
                {!! Form::hidden('task', 'deploy') !!}
                {!! Form::submit('Deploy', ['class' => 'btn btn-primary btn-lg']) !!}
                {!! Form::close() !!}
                {!! Form::open(['route' => ['projects.deployments.store', $project], 'method' => 'post', 'style' => 'display:inline']) !!}
                {!! Form::hidden('task', 'rollback') !!}
                {!! Form::submit('Rollback', ['class' => 'btn btn-danger btn-lg']) !!}
                {!! Form::close() !!}
            </div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><div align="center"></div></th>
                        <th><div align="center">#</div></th>
                        <th><div align="center">Task</div></th>
                        <th><div align="center">Started At</div></th>
                        <th><div align="center">Finished At</div></th>
                        <th><div align="center">Executed By</div></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($deployments as $deployment)
                        <tr>
                            <td>{!! $deployment->status() !!}</td>
                            <td>{{ $deployment->number }}</td>
                            <td>{{ $deployment->task }}</td>
                            <td>{{ $deployment->created_at }}</td>
                            <td>{{ $deployment->updated_at }}</td>
                            <td>{{ is_null($deployment->user) ? '' : $deployment->user->email }}</td>
                            <td>
                                {!! link_to_route('projects.deployments.show', 'Show', [$project, $deployment->number], ['class' => 'btn btn-default']) !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {!! $deployments->render() !!}
            </div>
        </div>
    </div>
</div>
@stop
