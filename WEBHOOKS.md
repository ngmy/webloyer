# Webhooks

Webloyer provide webhooks of GitHub to deploy a project.

## GitHub

### Usage

You must set "Execute By" in Create Project Page.<br>
If you want to use [GitHub webhook secret](https://developer.github.com/webhooks/securing/), you must also set "Secret".

### Endpoint

```
/webhook/v1/github/projects/:project_id/deployments
```
