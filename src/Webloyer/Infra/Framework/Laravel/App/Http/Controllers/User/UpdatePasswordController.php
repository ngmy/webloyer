<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Webloyer\App\Service\User\UpdatePasswordRequest as ServiceRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\User\UpdatePasswordRequest;

class UpdatePasswordController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdatePasswordRequest $request
     * @param string        $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdatePasswordRequest $request, string $id)
    {
        $serviceRequest = (new ServiceRequest())
            ->setId($id)
            ->setPassword($request->input('password'));
        $this->service->execute($serviceRequest);

        return redirect()->route('users.index');
    }
}
