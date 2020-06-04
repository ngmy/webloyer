<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Webloyer\App\Service\User\GetUserRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\ShowViewModel;

class ShowController extends BaseController
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
        $user = $this->service->execute($serviceRequest);

        return (new ShowViewModel($user))->view('webloyer::users.show');
    }
}
