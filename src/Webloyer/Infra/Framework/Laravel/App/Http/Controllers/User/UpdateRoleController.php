<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Webloyer\App\Service\User\UpdateRoleRequest as ServiceRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\User\UpdateRoleRequest;

class UpdateRoleController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateRoleRequest $request
     * @param string            $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateRoleRequest $request, string $id)
    {
        $serviceRequest = (new ServiceRequest())
            ->setId($id)
            ->setRoles($request->input('role'));
        $this->service->execute($serviceRequest);

        return redirect()->route('users.index');
    }
}
