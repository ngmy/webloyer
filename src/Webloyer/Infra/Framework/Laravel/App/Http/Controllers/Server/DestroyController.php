<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use Illuminate\Http\RedirectResponse;
use Webloyer\App\Service\Server\DeleteServerRequest;
use Webloyer\Domain\Model\Server\ServerDoesNotExistException;

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
        $serviceRequest = (new DeleteServerRequest())->setId($id);

        assert(!is_null($this->service));

        try {
            $this->service->execute($serviceRequest);
        } catch (ServerDoesNotExistException $exception) {
            abort(404);
        }

        return redirect()->route('servers.index');
    }
}
