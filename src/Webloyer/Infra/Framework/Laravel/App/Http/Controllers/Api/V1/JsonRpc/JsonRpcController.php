<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Api\V1\JsonRpc;

use App\Http\Controllers\Controller;
use Datto\JsonRpc\Server;
use Illuminate\Http\{
    JsonResponse,
    Request,
};

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
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        assert(file_get_contents('php://input') !== false);
        $requestJson = file_get_contents('php://input');
        $responseJson = $this->server->reply($requestJson);
        return response()->json($responseJson ? json_decode($responseJson, true) : []);
    }
}
