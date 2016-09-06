# Webhooks

Webloyer provide webhooks of GitHub to deploy a project.

## GitHub

### Usage

You must set "Execute By" in Create Project Page.  
If you want to use [GitHub webhook secret](https://developer.github.com/webhooks/securing/), you must also set "Secret".

### Endpoint

```
/webhook/github/v1/projects/:project_id/deployments
```
