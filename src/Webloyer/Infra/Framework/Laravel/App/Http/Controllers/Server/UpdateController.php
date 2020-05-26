<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use Webloyer\App\Service\Server\UpdateServerRequest;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Server\UpdateRequest;

class UpdateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param UpdateRequest $request
     * @param string        $id
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateRequest $request, string $id)
    {
        $serviceRequest = (new UpdateServerRequest())
            ->setId($id)
            ->setName($request->input('name'))
            ->setDescription($request->input('description'))
            ->setBody($request->input('body'));
        $this->service->execute($serviceRequest);

        return redirect()->route('servers.index');
    }
}
