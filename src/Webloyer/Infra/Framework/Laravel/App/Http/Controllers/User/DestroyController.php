<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Webloyer\App\Service\User\DeleteUserRequest;

class DestroyController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id)
    {
        $serviceRequest = (new DeleteUserRequest())->setId($id);
        assert(!is_null($this->service));
        $this->service->execute($serviceRequest);

        return redirect()->route('users.index');
    }
}
