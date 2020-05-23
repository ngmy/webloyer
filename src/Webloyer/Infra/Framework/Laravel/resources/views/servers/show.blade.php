@extends('app')

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
