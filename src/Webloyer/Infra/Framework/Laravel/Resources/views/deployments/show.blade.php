@extends('webloyer::app')

@section('content')
<div id="app">
    <deployment-item
        number="{{ $deployment->number }}"
        task="{{ $deployment->task }}"
        status="{{ $deployment->status }}"
        log="{{ $deploymentLog }}"
        started-at="{{ $deployment->startDate }}"
        finished-at="{{ $deployment->finishDate }}"
        executed-by="{{ $deployment->user->email }}"
        project-id="{{ $projectId }}"
        endpoint="{{ route('projects.deployments.show', [$projectId, $deployment->number]) }}"
        link-to-route="{{ route('projects.deployments.index', [$projectId]) }}"
    ></deployment-item>
</div>
@stop
