<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment;

use App\Http\Controllers\Controller;
use Common\App\Service\ApplicationService;

class BaseController extends Controller
{
    /** @var ApplicationService|null */
    protected $service;

    /**
     * Instantiate a new controller instance.
     *
     * @param ApplicationService|null $service
     * @return void
     */
    public function __construct(ApplicationService $service = null)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->service = $service;
    }
}
