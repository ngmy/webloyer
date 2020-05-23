@extends('webloyer::app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-md-offset-0">
            <div class="list-group">
                {!! link_to_route('users.edit', 'Edit User', [$user->id], ['class' => 'list-group-item']) !!}
                {!! link_to_route('users.password.change', 'Change Password', [$user->id], ['class' => 'list-group-item selected']) !!}
                {!! link_to_route('users.role.edit', 'Edit Role', [$user->id], ['class' => 'list-group-item']) !!}
                {!! link_to_route('users.api_token.edit', 'Edit API Token', [$user->id], ['class' => 'list-group-item']) !!}
            </div>
        </div>

        <div class="col-md-8 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">Change Password</div>
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

                    {!! Form::open(['route' => ['users.password.update', $user->id], 'method' => 'put', 'role' => 'form','class' => 'form-horizontal']) !!}
                        <div class="form-group required">
                            <label for="password" class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'data-editor' => 'php']) !!}
                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="password_confirmation" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation', 'data-editor' => 'php']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                {!! link_to_route('users.index', 'Cancel', [], ['class' => 'btn btn-danger']) !!}
                                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
