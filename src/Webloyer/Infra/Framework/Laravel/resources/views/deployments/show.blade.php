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
                        <td>{{ $deployment->status }}</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td><pre class="ansi_box"><code>{!! $deployment->log !!}</code></pre></td>
                    </tr>
                    <tr>
                        <th>Started At</th>
                        <td>{{ $deployment->startDate }}</td>
                    </tr>
                    <tr>
                        <th>Finished At</th>
                        <td>{{ $deployment->finishDate }}</td>
                    </tr>
                    <tr>
                        <th>Executed By</th>
                        <td>{{ $deployment->user->email }}</td>
                    </tr>
                </tbody>
            </table>
            {!! link_to_route('projects.deployments.index', 'Back', [$deployment->projectId], ['class' => 'btn btn-danger']) !!}
        </div>
    </div>
</div>
@stop
