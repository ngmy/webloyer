@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h1 class="page-header">Projects</h1>

			<div class="pull-right margin-bottom-lg">
				{!! link_to_route('projects.create', 'Create', [], ['class' => 'btn btn-primary btn-lg']) !!}
			</div>

			<table class="table table-striped">
				<thead>
					<tr>
						<th><div align="center">Name</div></th>
						<th><div align="center">Created At</div></th>
						<th><div align="center">Updated At</div></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach ($projects as $project)
						<tr>
							<td>{{ $project->name }}</td>
							<td>{{ $project->created_at }}</td>
							<td>{{ $project->updated_at }}</td>
							<td>
								{!! link_to_route('projects.deployments.index', 'Deployments', [$project->id], ['class' => 'btn btn-default']) !!}
								{!! link_to_route('projects.edit', 'Edit', [$project->id], ['class' => 'btn btn-default']) !!}
								{!! Form::open(['route' => ['projects.destroy', $project->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
								{!! Form::submit('Destroy', ['class' => 'btn btn-danger']) !!}
								{!! Form::close() !!}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<div class="text-center">
				{!! $projects->render() !!}
			</div>
		</div>
	</div>
</div>
@stop
