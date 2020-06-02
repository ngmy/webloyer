<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

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
     * @return \Illuminate\Http\Response
     */
    public function __invoke(RegenerateApiTokenRequest $request, string $id)
    {
        $serviceRequest = (new ServiceRequest())
            ->setId($id)
            ->setApiToken(Str::random(60));
        $this->service->execute($serviceRequest);

        return redirect()->route('users.index');
    }
}
