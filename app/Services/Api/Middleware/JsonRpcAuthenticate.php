<?php
declare(strict_types=1);

namespace App\Services\Api\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class JsonRpcAuthenticate
 * @package App\Services\Api\Middleware
 */
class JsonRpcAuthenticate
{

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * Authenticate constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $request
     * @param $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, $next)
    {
        $user = Auth::guard('api')->setRequest($this->request)->user();
        if (is_null($user)) {
            throw new AuthenticationException('Wrong credentials!');
        }
        return $next($request);
    }
}
