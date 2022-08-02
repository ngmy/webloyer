<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use App\Repositories\Project\ProjectInterface;
use Illuminate\Http\Request;

/**
 * Class VerifyGithubWebhookSecret
 * @package App\Http\Middleware
 */
class VerifyGithubWebhookSecret
{
    /**
     * @var ProjectInterface
     */
    protected ProjectInterface $projectRepository;

    /**
     * VerifyGithubWebhookSecret constructor.
     * @param ProjectInterface $projectRepository
     */
    public function __construct(ProjectInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $secret = $request->projects->github_webhook_secret;

        if (isset($secret)) {
            $signature = 'sha1='.hash_hmac('sha1', $request->getContent(), $secret);

            if ($signature !== $request->header('X-Hub-Signature')) {
                abort(401);
            }
        }

        return $next($request);
    }
}
