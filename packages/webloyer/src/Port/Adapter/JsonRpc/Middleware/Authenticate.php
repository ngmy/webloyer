<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\JsonRpc\Middleware;

use Illuminate\Http\Request;
use Auth;
use JsonRPC\MiddlewareInterface;
use JsonRPC\Exception\AuthenticationFailureException;

class Authenticate implements MiddlewareInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function execute($username, $password, $procedureName)
    {
        $user = Auth::guard('api')->setRequest($this->request)->user();

        if (is_null($user)) {
            throw new AuthenticationFailureException('Wrong credentials!');
        }
    }
}
