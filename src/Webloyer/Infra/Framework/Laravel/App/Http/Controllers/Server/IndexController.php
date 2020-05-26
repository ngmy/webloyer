<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Server\IndexRequest;

class IndexController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param IndexRequest $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(IndexRequest $request)
    {
        $this->service->serversDataTransformer()->setPerPage(10);
        $servers = $this->service->execute();

        return view('webloyer::servers.index')->with('servers', $servers);
    }
}
