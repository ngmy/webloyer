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
| project_id | integer | Project id. |

#### Example Request

```
curl -X POST -d '{"jsonrpc":"2.0","id":1,"method":"deploy","params":{"project_id":1}}' -H "Authorization: Bearer aiSPTQE2nMnmHtyfjZenfI5dcb52zANE30n5t1gL5H2BwPpXz9GIVYKVFE8x" -H "Accept: application/json-rpc" http://webloyer.local/api/v1/jsonrpc
```

#### Example Response

```json
{"jsonrpc":"2.0","result":{"id":11,"project_id":1,"number":11,"task":"deploy","status":null,"message":null,"user_id":1,"created_at":"2016-10-15 18:25:31","updated_at":"2016-10-15 18:25:31"},"id":1}
```

### rollback

Roll back a project to a previous deployment.

#### Parameters

| Name | Type | Description |
| --- | --- | --- |
| project_id | integer | Project id. |

#### Example Request

```
curl -X POST -d '{"jsonrpc":"2.0","id":1,"method":"rollback","params":{"project_id":1}}' -H "Authorization: Bearer aiSPTQE2nMnmHtyfjZenfI5dcb52zANE30n5t1gL5H2BwPpXz9GIVYKVFE8x" -H "Accept: application/json-rpc" http://webloyer.local/api/v1/jsonrpc
```

#### Example Response

```json
{"jsonrpc":"2.0","result":{"id":13,"project_id":1,"number":13,"task":"rollback","status":null,"message":null,"user_id":1,"created_at":"2016-10-15 18:36:22","updated_at":"2016-10-15 18:36:22"},"id":1}
```
