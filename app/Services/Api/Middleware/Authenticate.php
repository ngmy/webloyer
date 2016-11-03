<?php

namespace App\Services\Api\Middleware;

use JsonRPC\MiddlewareInterface;
use JsonRPC\Exception\AuthenticationFailureException;
use Illuminate\Http\Request;
use Auth;

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
