@extends('webloyer::app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create Project</div>
                <div class="panel-body">
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

                    {!! Form::open(['route' => 'projects.store', 'method' => 'post', 'role' => 'form','class' => 'form-horizontal']) !!}
                        <div class="form-group required">
                            <label for="name" class="col-md-4 control-label">Project Name</label>
                            <div class="col-md-6">
                                {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="recipe_id" class="col-md-4 control-label">Recipe</label>
                            <div class="col-md-6">
                                {!! Form::select('recipe_id[]', $recipeSelectBoxOptions, null, ['class' => 'form-control multi-select', 'id' => 'recipe_id', 'multiple' => 'multiple']) !!}
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="server_id" class="col-md-4 control-label">Server</label>
                            <div class="col-md-6">
                                {!! Form::select('server_id', $serverSelectBoxOptions, null, ['class' => 'form-control', 'id' => 'server_id']) !!}
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="repository" class="col-md-4 control-label">Repository URL</label>
                            <div class="col-md-6">
                                {!! Form::text('repository', null, ['class' => 'form-control', 'id' => 'repository']) !!}
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="stage" class="col-md-4 control-label">Stage</label>
                            <div class="col-md-6">
                                {!! Form::text('stage', null, ['class' => 'form-control', 'id' => 'stage']) !!}
                            </div>
                        </div>
                        <hr>
                        <h5>Overriding Server Definition</h5>
                        <div class="form-group">
                            <label for="deploy_path" class="col-md-4 control-label">Deploy Path</label>
                            <div class="col-md-6">
                                {!! Form::text('deploy_path', null, ['class' => 'form-control', 'id' => 'deploy_path']) !!}
                            </div>
                        </div>
                        <hr>
                        <h5>E-Mail Notification</h5>
                        <div class="form-group">
                            <label for="email_notification_recipient" class="col-md-4 control-label">Recipient</label>
                            <div class="col-md-6">
                                {!! Form::email('email_notification_recipient', null, ['class' => 'form-control', 'id' => 'email_notification_recipient']) !!}
                            </div>
                        </div>
                        <hr>
                        <h5>Discard Old Deployments</h5>
                        <div class="form-group">
                            <label for="days_to_keep_deployments" class="col-md-4 control-label">Days To Keep Deployments</label>
                            <div class="col-md-6">
                                {!! Form::text('days_to_keep_deployments', null, ['class' => 'form-control', 'id' => 'days_to_keep_deployments']) !!}
                            </div>
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label class"col-md-4">
                                        {!! Form::checkbox('keep_last_deployment', true) !!}
                                        Keep Last Deployment
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="max_number_of_deployments_to_keep" class="col-md-4 control-label">Max # Of Deployments To Keep</label>
                            <div class="col-md-6">
                                {!! Form::text('max_number_of_deployments_to_keep', null, ['class' => 'form-control', 'id' => 'max_number_of_deployments_to_keep']) !!}
                            </div>
                        </div>
                        <hr>
                        <h5>GitHub Webhook</h5>
                        <div class="form-group">
                            <label for="github_webhook_secret" class="col-md-4 control-label">Secret</label>
                            <div class="col-md-6">
                                {!! Form::text('github_webhook_secret', null, ['class' => 'form-control', 'id' => 'github_webhook_secret']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="github_webhook_user_id" class="col-md-4 control-label">Execute By</label>
                            <div class="col-md-6">
                                {!! Form::select('github_webhook_user_id', $userSelectBoxOptions, null, ['class' => 'form-control', 'id' => 'github_webhook_user_id']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                {!! link_to_route('projects.index', 'Cancel', [], ['class' => 'btn btn-danger']) !!}
                                {!! Form::submit('Store', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::hidden('recipe_id_order', null, ['id' => 'recipe_id_order']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
