<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use Illuminate\Http\RedirectResponse;
use Webloyer\App\Service\Server\DeleteServerRequest;

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
        $this->service->execute($serviceRequest);

        return redirect()->route('servers.index');
    }
}
