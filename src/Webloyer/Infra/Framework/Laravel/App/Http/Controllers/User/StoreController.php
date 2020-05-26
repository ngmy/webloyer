<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Illuminate\Support\Str;
use Webloyer\App\Service\User\CreateUserRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\User\StoreRequest;

class StoreController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreRequest $request)
    {
        $serviceRequest = (new CreateUserRequest())
            ->setEmail($request->input('email'))
            ->setName($request->input('name'))
            ->setPassword($request->input('password'))
            ->setApiToken(Str::random(60));
        $this->service->execute($serviceRequest);

        return redirect()->route('users.index');
    }
}
