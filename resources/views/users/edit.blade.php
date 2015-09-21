@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-2 col-md-offset-0">
			<div class="list-group">
				{!! link_to_route('users.edit', 'Edit User', [$user->id], ['class' => 'list-group-item selected']) !!}
				{!! link_to_route('users.password.change', 'Change Password', [$user->id], ['class' => 'list-group-item']) !!}
				{!! link_to_route('users.role.edit', 'Edit Role', [$user->id], ['class' => 'list-group-item']) !!}
			</div>
		</div>

		<div class="col-md-8 col-md-offset-0">
			<div class="panel panel-default">
				<div class="panel-heading">Edit User</div>
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

					{!! Form::open(['route' => ['users.update', $user->id], 'method' => 'put', 'role' => 'form','class' => 'form-horizontal']) !!}
						<div class="form-group required">
							<label for="name" class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								{!! Form::text('name', $user->name, ['class' => 'form-control', 'id' => 'name']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="email" class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								{!! Form::email('email', $user->email, ['class' => 'form-control', 'id' => 'email']) !!}
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
