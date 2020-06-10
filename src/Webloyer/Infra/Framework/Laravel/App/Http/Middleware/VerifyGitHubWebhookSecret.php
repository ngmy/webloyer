<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Middleware;

use Closure;
use Webloyer\App\Service\Project\{
    GetProjectRequest,
    GetProjectService,
};

class VerifyGitHubWebhookSecret
{
    private $service;

    public function __construct(GetProjectService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $serviceRequest = (new GetProjectRequest())->setId($request->route()->parameter('project'));
        $project = $this->service->execute($serviceRequest);

        $secret = $project->gitHubWebhookSecret;

        if (isset($secret)) {
            $signature = 'sha1=' . hash_hmac('sha1', $request->getContent(), $secret);

            if ($signature != $request->header('X-Hub-Signature')) {
                abort(401);
            }
        }

        return $next($request);
    }
}
