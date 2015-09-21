@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<table class="table">
				<tbody>
					<tr>
						<th>Project Name</th>
						<td>{{ $project->name }}</td>
					</tr>
					@foreach ($projectRecipe as $i => $recipe)
						<tr>
							@if ($i === 0)
								<th rowspan="{{ count($projectRecipe) }}">Recipe</th>
							@endif
							<td>{{ $recipe['name'] }}</td>
						</tr>
					@endforeach
					<tr>
						<th>Server</th>
						<td>{{ $projectServer->name }}</td>
					</tr>
					<tr>
						<th>Repository URL</th>
						<td>{{ $project->repository }}</td>
					</tr>
					<tr>
						<th>Stage</th>
						<td>{{ $project->stage }}</td>
					</tr>
				</tbody>
			</table>
			{!! link_to_route('projects.index', 'Back', [], ['class' => 'btn btn-danger']) !!}
			@if (Auth::user()->can('update.project'))
				{!! link_to_route('projects.edit', 'Edit', [$project->id], ['class' => 'btn btn-primary']) !!}
			@endif
		</div>
	</div>
</div>
@stop


