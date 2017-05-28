@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <table class="table">
                <tbody>
                    <tr>
                        <th>#</th>
                        <td>{{ $deployment->deploymentId()->id() }}</td>
                    </tr>
                    <tr>
                        <th>Task</th>
                        <td>{{ $deployment->task()->value() }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $deployment->status()->Text() }}</td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td><pre class="ansi_box"><code>{!! $deployment->messageHtml() !!}</code></pre></td>
                    </tr>
                    <tr>
                        <th>Started At</th>
                        <td>{{ $deployment->createdAt() }}</td>
                    </tr>
                    <tr>
                        <th>Finished At</th>
                        <td>{{ $deployment->updatedAt() }}</td>
                    </tr>
                    <tr>
                        <th>Executed By</th>
                        <td>{{ is_null($deployedUser) ? '' : $deployedUser->email() }}</td>
                    </tr>
                </tbody>
            </table>
            {!! link_to_route('projects.deployments.index', 'Back', [$deployment->projectId()->id()], ['class' => 'btn btn-danger']) !!}
        </div>
    </div>
</div>
@stop
