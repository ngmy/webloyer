@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Create User</div>
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

					{!! Form::open(['route' => 'users.store', 'method' => 'post', 'role' => 'form','class' => 'form-horizontal']) !!}
						<div class="form-group required">
							<label for="name" class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								{!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="email" class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								{!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="password" class="col-md-4 control-label">Password</label>
							<div class="col-md-6">
								{!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'type' => 'password']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="password_confirmation" class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								{!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation', 'type' => 'password']) !!}
							</div>
						</div>
						<div class="form-group">
							<label for="role" class="col-md-4 control-label">Role</label>
							<div class="col-md-6">
								@foreach ($roles as $role)
									<div class="checkbox">
										<label>
											{!! Form::checkbox('role[]', $role->id) !!}
											{{ $role->name }}
										</label>
									</div>
								@endforeach
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								{!! link_to_route('users.index', 'Cancel', [], ['class' => 'btn btn-danger']) !!}
								{!! Form::submit('Store', ['class' => 'btn btn-primary']) !!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@stop

