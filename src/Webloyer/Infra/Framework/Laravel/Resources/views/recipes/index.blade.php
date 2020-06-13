@extends('webloyer::app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1 class="page-header">Recipes</h1>

            @if (Auth::user()->hasPermission('create.recipe'))
                <div class="pull-right margin-bottom-lg">
                    {!! link_to_route('recipes.create', 'Create', [], ['class' => 'btn btn-primary btn-lg']) !!}
                </div>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><div align="center">Name</div></th>
                        <th><div align="center">Used By</div></th>
                        <th><div align="center">Created At</div></th>
                        <th><div align="center">Updated At</div></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recipes as $recipe)
                        <tr>
                            <td>{{ $recipe->name }}</td>
                            <td><div align="right">{{ $projectCountOf[$recipe->id] }}</div></td>
                            <td>{{ $recipe->createdAt }}</td>
                            <td>{{ $recipe->updatedAt }}</td>
                            <td>
                                {!! link_to_route('recipes.show', 'Show', [$recipe->id], ['class' => 'btn btn-default']) !!}
                                @if (Auth::user()->hasPermission('update.recipe'))
                                    {!! link_to_route('recipes.edit', 'Edit', [$recipe->id], ['class' => 'btn btn-default']) !!}
                                @endif
                                @if (Auth::user()->hasPermission('delete.recipe'))
                                    {!! Form::open(['route' => ['recipes.destroy', $recipe->id], 'method' => 'delete', 'style' => 'display:inline']) !!}
                                    {!! Form::submit('Destroy', ['class' => 'btn btn-danger']) !!}
                                    {!! Form::close() !!}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {!! $recipes->links() !!}
            </div>
        </div>
    </div>
</div>
@stop
