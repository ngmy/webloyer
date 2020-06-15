<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use Webloyer\App\Service\Server\CreateServerRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Server\StoreRequest;

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
        $serviceRequest = (new CreateServerRequest())
            ->setName($request->input('name'))
            ->setDescription($request->input('description'))
            ->setBody($request->input('body'));
        assert(!is_null($this->service));
        $this->service->execute($serviceRequest);

        return redirect()->route('servers.index');
    }
}
