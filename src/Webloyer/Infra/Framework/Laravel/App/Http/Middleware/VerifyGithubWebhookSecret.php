<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Middleware;

use Closure;

class VerifyGithubWebhookSecret
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $secret = $request->project->githubWebhookSecret();

        if (isset($secret)) {
            $signature = 'sha1=' . hash_hmac('sha1', $request->getContent(), $secret);

            if ($signature !== $request->header('X-Hub-Signature')) {
                abort(401);
            }
        }

        return $next($request);
    }
}
