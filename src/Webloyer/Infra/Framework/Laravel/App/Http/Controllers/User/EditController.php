<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Webloyer\App\Service\User\GetUserRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\EditViewModel;

class EditController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(string $id)
    {
        $serviceRequest = (new GetUserRequest())->setId($id);
        assert(!is_null($this->service));
        $user = $this->service->execute($serviceRequest);

        return (new EditViewModel($user))->view('webloyer::users.edit');
    }
}
