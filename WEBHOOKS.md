# Webhooks

Webloyer provide webhooks of GitHub and Bitbucket to deploy a project.

## Bitbucket

### Usage

You must set "Execute By" in Create Project Page.<br>
If you want to use a secret key, you must also set "Secret" and specify it as GET parameter on payload url.
Ex: 
- Secret key: deploykey
- Secret Base 64 Encode: ZGVwbG95a2V5
- Result: https://www.mydeploy.site/webhook/bitbucket/v1/projects/1/deployments?secret=ZGVwbG95a2V5

### Endpoint

```
/webhook/bitbucket/v1/projects/:project_id/deployments
```


## GitHub

### Usage

You must set "Execute By" in Create Project Page.<br>
If you want to use [GitHub webhook secret](https://developer.github.com/webhooks/securing/), you must also set "Secret".

### Endpoint

```
/webhook/github/v1/projects/:project_id/deployments
```
