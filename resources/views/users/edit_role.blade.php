@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-md-offset-0">
            <div class="list-group">
                {!! link_to_route('users.edit', 'Edit User', [$user->userId()->id()], ['class' => 'list-group-item']) !!}
                {!! link_to_route('users.password.change', 'Change Password', [$user->userId()->id()], ['class' => 'list-group-item']) !!}
                {!! link_to_route('users.role.edit', 'Edit Role', [$user->userId()->id()], ['class' => 'list-group-item selected']) !!}
                {!! link_to_route('users.api_token.edit', 'Edit API Token', [$user->userId()->id()], ['class' => 'list-group-item']) !!}
            </div>
        </div>

        <div class="col-md-8 col-md-offset-0">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Role</div>
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

                    {!! Form::open(['route' => ['users.role.update', $user->userId()->id()], 'method' => 'put', 'role' => 'form','class' => 'form-horizontal']) !!}
                        <div class="form-group">
                            <label for="role" class="col-md-4 control-label">Role</label>
                            <div class="col-md-6">
                                @foreach ($roles as $role)
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('role[]', $role->roleId()->id(), $user->hasRoleId($role->roleId())) !!}
                                            {{ $role->name() }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                {!! link_to_route('users.index', 'Cancel', [], ['class' => 'btn btn-danger']) !!}
                                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>
                        {!! Form::hidden('concurrency_version', $user->concurrencyVersion(), ['id' => 'concurrency_version']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
