<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use Webloyer\App\Service\Project\{
    GetProjectRequest,
    GetProjectService,
};

class VerifyGitHubWebhookSecret
{
    /** @var GetProjectService */
    private $service;

    /**
     * @param GetProjectService $service
     * @return void
     */
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
        assert($request->route() instanceof Route);
        $serviceRequest = (new GetProjectRequest())->setId($request->route()->parameter('project'));
        $project = $this->service->execute($serviceRequest);

        $secret = $project->gitHubWebhookSecret;

        if (isset($secret)) {
            assert(is_string($request->getContent()));
            $signature = 'sha1=' . hash_hmac('sha1', $request->getContent(), $secret);

            if ($signature != $request->header('X-Hub-Signature')) {
                abort(401);
            }
        }

        return $next($request);
    }
}
