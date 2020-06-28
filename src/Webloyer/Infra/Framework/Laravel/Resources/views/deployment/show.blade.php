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
        executed-by="{{ $deploymentUserEmail }}"
        project-id="{{ $projectId }}"
        deployment-show-api-url="{{ route('projects.deployments.show', [$projectId, $deployment->number]) }}"
        deployment-index-link="{{ route('projects.deployments.index', [$projectId]) }}"
    ></deployment-item>
</div>
@stop
