<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Webloyer\App\Service\User\UpdatePasswordRequest as ServiceRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\User\UpdatePasswordRequest;

class UpdatePasswordController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdatePasswordRequest $request
     * @param string        $id
     * @return RedirectResponse
     */
    public function __invoke(UpdatePasswordRequest $request, string $id): RedirectResponse
    {
        $serviceRequest = (new ServiceRequest())
            ->setId($id)
            ->setPassword(Hash::make($request->input('password')));
        assert(!is_null($this->service));
        $this->service->execute($serviceRequest);

        return redirect()->route('users.index');
    }
}
