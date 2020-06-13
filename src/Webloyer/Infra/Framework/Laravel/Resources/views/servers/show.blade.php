@extends('webloyer::app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{ $server->name }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $server->description }}</td>
                    </tr>
                    <tr>
                        <th>Body</th>
                        <td><pre><code>{{ $server->body }}</code></pre></td>
                    </tr>
                    @if ($isServerHasProjects)
                        @foreach ($server->projects as $project)
                            <tr>
                                @if ($loop->first)
                                    <th rowspan="{{ $serverProjectCount }}">Used By</th>
                                @endif
                                <td>{!! link_to_route('projects.show', $project->name, $project->id) !!}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <th>Used By</th>
                            <td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {!! link_to_route('servers.index', 'Back', [], ['class' => 'btn btn-danger']) !!}
            @if (Auth::user()->hasPermission('update.server'))
                {!! link_to_route('servers.edit', 'Edit', [$server->id], ['class' => 'btn btn-primary']) !!}
            @endif
        </div>
    </div>
</div>
@stop
