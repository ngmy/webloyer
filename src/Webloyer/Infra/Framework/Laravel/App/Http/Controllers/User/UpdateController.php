<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Webloyer\App\Service\User\UpdateUserRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\User\UpdateRequest;

class UpdateController extends BaseController
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
            ->setEmail($request->input('email'))
            ->setName($request->input('name'));
        $this->service->execute($serviceRequest);

        return redirect()->route('users.index');
    }
}