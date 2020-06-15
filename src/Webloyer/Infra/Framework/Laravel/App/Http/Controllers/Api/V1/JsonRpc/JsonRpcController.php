<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Api\V1\JsonRpc;

use App\Http\Controllers\Controller;
use Datto\JsonRpc\Server;
use Illuminate\Http\Request;

class JsonRpcController extends Controller
{
    /** @var Server */
    private $server;

    /**
     * @param Server $server
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        assert(file_get_contents('php://input') !== false);
        $json = file_get_contents('php://input');
        return $this->server->reply($json);
    }
}
