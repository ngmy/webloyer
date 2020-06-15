# Web APIs

Webloyer provide Web APIs.<br>
For example, you can use these for Git hooks.

## Protocol

The Web APIs use the [JSON-RPC 2.0](http://www.jsonrpc.org/specification) protocol.<br>
You must call the Web APIs with a **POST** HTTP request.<br>
And you must call the Web APIs with a `Accept` header set to `application/json-rpc`.

## Endpoint

```
/api/v1/jsonrpc
```

## Authentication

You must provide your API token into a `Authorization: Bearer` header.<br>
You can see your API token in Edit API Token Page ([Users] -> [Edit] -> [Edit API Token]).

## API Reference

### deploy

Deploy a project.

#### Parameters

| Name | Type | Description |
| --- | --- | --- |
| projectId | string | Project id. |

#### Example Request

```
curl -X POST -d '{"jsonrpc":"2.0","id":1,"method":"deploy","params":{"projectId":"90cc3821-1bf0-49ad-8424-2385335783ba"}}' -H "Authorization: Bearer aiSPTQE2nMnmHtyfjZenfI5dcb52zANE30n5t1gL5H2BwPpXz9GIVYKVFE8x" -H "Accept: application/json-rpc" http://webloyer.local/api/v1/jsonrpc
```

#### Example Response

```json
{"jsonrpc":"2.0","id":1,"result":{"projectId":"90cc3821-1bf0-49ad-8424-2385335783ba","number":8,"task":"deploy","status":"queued","log":"","executor":"90cc17bc-2f89-4303-a17c-119921febadc","requestDate":"2020-06-15 16:05:36","startDate":null,"finishDate":null,"user":null,"surrogateId":8,"createdAt":"2020-06-15 16:05:36","updatedAt":"2020-06-15 16:05:36"}}
```

### rollback

Roll back a project to a previous deployment.

#### Parameters

| Name | Type | Description |
| --- | --- | --- |
| projectId | string | Project id. |

#### Example Request

```
curl -X POST -d '{"jsonrpc":"2.0","id":1,"method":"rollback","params":{"projectId":"90cc3821-1bf0-49ad-8424-2385335783ba"}}' -H "Authorization: Bearer aiSPTQE2nMnmHtyfjZenfI5dcb52zANE30n5t1gL5H2BwPpXz9GIVYKVFE8x" -H "Accept: application/json-rpc" http://webloyer.local/api/v1/jsonrpc
```

#### Example Response

```json
{"jsonrpc":"2.0","id":1,"result":{"projectId":"90cc3821-1bf0-49ad-8424-2385335783ba","number":9,"task":"rollback","status":"queued","log":"","executor":"90cc17bc-2f89-4303-a17c-119921febadc","requestDate":"2020-06-15 16:07:11","startDate":null,"finishDate":null,"user":null,"surrogateId":9,"createdAt":"2020-06-15 16:07:11","updatedAt":"2020-06-15 16:07:11"}}
```
