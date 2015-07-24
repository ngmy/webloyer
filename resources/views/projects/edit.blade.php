@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Edit Project</div>
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

					{!! Form::open(['route' => ['projects.update', $project->id], 'method' => 'put', 'role' => 'form','class' => 'form-horizontal']) !!}
						<div class="form-group required">
							<label for="name" class="col-md-4 control-label">Project Name</label>
							<div class="col-md-6">
								{!! Form::text('name', $project->name, ['class' => 'form-control', 'id' => 'name']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="recipe_id" class="col-md-4 control-label">Recipe</label>
							<div class="col-md-6">
								{!! Form::select('recipe_id', $recipes, $project->recipe_id, ['class' => 'form-control', 'id' => 'recipe_id']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="servers" class="col-md-4 control-label">Server List File Path</label>
							<div class="col-md-6">
								{!! Form::text('servers', $project->servers, ['class' => 'form-control', 'id' => 'servers']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="repository" class="col-md-4 control-label">Repository URL</label>
							<div class="col-md-6">
								{!! Form::text('repository', $project->repository, ['class' => 'form-control', 'id' => 'repository']) !!}
							</div>
						</div>
						<div class="form-group required">
							<label for="stage" class="col-md-4 control-label">Stage</label>
							<div class="col-md-6">
								{!! Form::text('stage', $project->stage, ['class' => 'form-control', 'id' => 'stage']) !!}
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								{!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
								{!! link_to_route('projects.index', 'Cancel', [], ['class' => 'btn btn-danger']) !!}
							</div>
						</div>
					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@stop
