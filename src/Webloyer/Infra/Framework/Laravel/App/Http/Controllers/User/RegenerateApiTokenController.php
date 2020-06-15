<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Webloyer\App\Service\User\RegenerateApiTokenRequest as ServiceRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\User\RegenerateApiTokenRequest;

class RegenerateApiTokenController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param RegenerateApiTokenRequest $request
     * @param string                    $id
     * @return RedirectResponse
     */
    public function __invoke(RegenerateApiTokenRequest $request, string $id): RedirectResponse
    {
        $serviceRequest = (new ServiceRequest())
            ->setId($id)
            ->setApiToken(Str::random(60));
        assert(!is_null($this->service));
        $this->service->execute($serviceRequest);

        return redirect()->route('users.index');
    }
}
