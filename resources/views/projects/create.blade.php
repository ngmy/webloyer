@extends('app')

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
								{!! Form::select('recipe_id[]', $recipes, null, ['class' => 'form-control multi-select', 'id' => 'recipe_id', 'multiple' => 'multiple']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="server_id" class="col-md-4 control-label">Server</label>
							<div class="col-md-6">
								{!! Form::select('server_id', $servers, null, ['class' => 'form-control', 'id' => 'server_id']) !!}
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
						<h5>E-Mail Notification</h5>
						<div class="form-group">
							<label for="email_notification_recipient" class="col-md-4 control-label">Recipient</label>
							<div class="col-md-6">
								{!! Form::email('email_notification_recipient', null, ['class' => 'form-control', 'id' => 'email_notification_recipient']) !!}
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
