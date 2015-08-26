@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Edit Server</div>
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

					{!! Form::open(['route' => ['servers.update', $server->id], 'method' => 'put', 'role' => 'form','class' => 'form-horizontal']) !!}
						<div class="form-group required">
							<label for="name" class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								{!! Form::text('name', $server->name, ['class' => 'form-control', 'id' => 'name']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="description" class="col-md-4 control-label">Description</label>
							<div class="col-md-6">
								{!! Form::textarea('description', $server->description, ['class' => 'form-control', 'id' => 'description']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="body" class="col-md-4 control-label">Body</label>
							<div class="col-md-6">
								{!! Form::textarea('body', $server->body, ['class' => 'form-control', 'id' => 'body', 'data-editor' => 'yaml']) !!}
								<p class="help-block">You can define a <a href="http://deployer.org/docs/servers">server list YAML file</a> here.</p>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								{!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
								{!! link_to_route('servers.index', 'Cancel', [], ['class' => 'btn btn-danger']) !!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@stop
