Deployment of {{ $project->name() }} #{{ $deployment->deploymentId()->id() }} finished

Task: {{ $deployment->task()->value() }}

Status: {{ $deployment->status()->text() }}

Started At: {{ $deployment->createdAt() }}

Finished At: {{ $deployment->updatedAt() }}


Executed By: {{ is_null($deployedUser) ? '' : $deployedUser->email() }}


Deployment URL: {{ route('projects.deployments.show', [$project->projectId()->id(), $deployment->deploymentId()->id()]) }}


Log:

{!! $deployment->messageText() !!}
