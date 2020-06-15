<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Webloyer\App\Service\User\GetUserRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\EditApiTokenViewModel;

class EditApiTokenController extends BaseController
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

        return (new EditApiTokenViewModel($user))->view('webloyer::users.edit_api_token');
    }
}
