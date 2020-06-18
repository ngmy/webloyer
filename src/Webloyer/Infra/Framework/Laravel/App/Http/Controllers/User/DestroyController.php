<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User;

use Illuminate\Http\RedirectResponse;
use Webloyer\App\Service\User\DeleteUserRequest;
use Webloyer\Domain\Model\User\UserDoesNotExistException;

class DestroyController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param string $id
     * @return RedirectResponse
     */
    public function __invoke(string $id): RedirectResponse
    {
        $serviceRequest = (new DeleteUserRequest())->setId($id);

        assert(!is_null($this->service));

        try {
            $this->service->execute($serviceRequest);
        } catch (UserDoesNotExistException $exception) {
            abort(404);
        }

        return redirect()->route('users.index');
    }
}
