<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Illuminate\Http\RedirectResponse;
use Webloyer\App\Service\User\UpdateUserRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\User\UpdateRequest;

class UpdateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateRequest $request
     * @param string        $id
     * @return RedirectResponse
     */
    public function __invoke(UpdateRequest $request, string $id): RedirectResponse
    {
        $serviceRequest = (new UpdateUserRequest())
            ->setId($id)
            ->setEmail($request->input('email'))
            ->setName($request->input('name'));
        assert(!is_null($this->service));
        $this->service->execute($serviceRequest);

        return redirect()->route('users.index');
    }
}
