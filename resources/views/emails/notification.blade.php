Deployment of {{ $project->name }} #{{ $deployment->number }} finished

Task: {{ $deployment->task }}

Status: {{ $deployment->statusText() }}

Started At: {{ $deployment->created_at }}

Finished At: {{ $deployment->updated_at }}


Executed By: {{ $deployment->user->email }}


Deployment URL: {{ route('projects.deployments.show', [$project->id, $deployment->number]) }}


Log:

{!! $deployment->messageText() !!}
