<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Middleware;

use Closure;

class ResponseJsonIfRequestedByQueryParameter
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->input('format') == 'json') {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}
