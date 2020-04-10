<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\Project\ProjectInterface;

class VerifyGithubWebhookSecret
{
    protected $projectRepository;

    public function __construct(ProjectInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $secret = $request->project->github_webhook_secret;

        if (isset($secret)) {
            $signature = 'sha1='.hash_hmac('sha1', $request->getContent(), $secret);

            if ($signature !== $request->header('X-Hub-Signature')) {
                abort(401);
            }
        }

        return $next($request);
    }
}
