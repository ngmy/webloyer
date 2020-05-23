@extends('webloyer::app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table">
                <tbody>
                    <tr>
                        <th>#</th>
                        <td>{{ $deployment->number }}</td>
                    </tr>
                    <tr>
                        <th>Task</th>
                        <td>{{ $deployment->task }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $deployment->statusText() }}</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td><pre class="ansi_box"><code>{!! $deployment->message() !!}</code></pre></td>
                    </tr>
                    <tr>
                        <th>Started At</th>
                        <td>{{ $deployment->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Finished At</th>
                        <td>{{ $deployment->updated_at }}</td>
                    </tr>
                    <tr>
                        <th>Executed By</th>
                        <td>{{ is_null($deployment->user) ? '' : $deployment->user->email }}</td>
                    </tr>
                </tbody>
            </table>
            {!! link_to_route('projects.deployments.index', 'Back', [$deployment->project_id], ['class' => 'btn btn-danger']) !!}
        </div>
    </div>
</div>
@stop
