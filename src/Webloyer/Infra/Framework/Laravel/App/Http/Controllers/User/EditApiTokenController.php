<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Spatie\ViewModels\ViewModel;
use Webloyer\App\Service\User\GetUserRequest;
use Webloyer\Domain\Model\User\UserDoesNotExistException;
use Webloyer\Infra\Framework\Laravel\Resources\ViewModels\User\EditApiTokenViewModel;

class EditApiTokenController extends BaseController
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

        try {
            $user = $this->service->execute($serviceRequest);
        } catch (UserDoesNotExistException $exception) {
            abort(404);
        }

        return (new EditApiTokenViewModel($user))->view('webloyer::users.edit-api-token');
    }
}
