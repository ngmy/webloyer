<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Spatie\ViewModels\ViewModel;
use Webloyer\App\Service\User\GetUserRequest;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\ShowViewModel;

class ShowController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return ViewModel
     */
    public function __invoke(string $id): ViewModel
    {
        $serviceRequest = (new GetUserRequest())->setId($id);
        assert(!is_null($this->service));
        $user = $this->service->execute($serviceRequest);

        return (new ShowViewModel($user))->view('webloyer::users.show');
    }
}
