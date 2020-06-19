@extends('webloyer::app')

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

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h1 class="page-header">Deployments</h1>

            <div class="pull-right margin-bottom-lg">
                {!! Form::open(['route' => ['projects.deployments.deploy', $projectId], 'method' => 'post', 'style' => 'display:inline']) !!}
                    {!! Form::submit('Deploy', ['class' => 'btn btn-primary btn-lg']) !!}
                {!! Form::close() !!}
                {!! Form::open(['route' => ['projects.deployments.rollback', $projectId], 'method' => 'post', 'style' => 'display:inline']) !!}
                    {!! Form::submit('Rollback', ['class' => 'btn btn-danger btn-lg']) !!}
                {!! Form::close() !!}
            </div>

            <div id="app">
                <deployment-items
                    :deployments='@json($deployments->toArray()["data"])'
                    :deployment-status='@json($deploymentStatus)'
                    :deployment-user-email-of='@json($deploymentUserEmailOf)'
                    :deployment-links='@json($deploymentLinks)'
                    :deployment-api-urls='@json($deploymentApiUrls)'
                ></deployment-items>
            </div>
            <div class="text-center">
                {!! $deployments->render() !!}
            </div>
        </div>
    </div>
</div>
@stop
