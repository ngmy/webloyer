<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Webloyer\App\Service\User\UpdateUserRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\User\UpdateRequest;

class UpdateRoleController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateRequest $request
     * @param string        $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateRequest $request, string $id)
    {
        $serviceRequest = (new UpdateUserRequest())
            ->setId($id)
            ->setRoles($request->input('role'));
        $this->service->execute($serviceRequest);

        return redirect()->route('users.index');
    }
}
