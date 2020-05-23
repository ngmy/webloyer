<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use Webloyer\Infra\Framework\Laravel\App\Http\Requests\Server as ServerRequest;
use Webloyer\App\Service\Server\{
    CreateServerRequest,
    CreateServerService,
    DeleteServerRequest,
    DeleteServerService,
    GetServerRequest,
    GetServerService,
    GetServersRequest,
    GetServersService,
    UpdateServerRequest,
    UpdateServerService,
};
use Webloyer\Domain\Model\Server as ServerDomainModel;

class ServerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('acl');
    }

    /**
     * Display a listing of the resource.
     *
     * @param ServerRequest\IndexRequest $request
     * @return Response
     */
    public function index(ServerRequest\IndexRequest $request, GetServersService $service)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $serviceRequest = (new GetServersRequest())
            ->setPage($page)
            ->setPerPage($perPage);
        $servers = $service->execute($serviceRequest);

        return view('webloyer::servers.index')->with('servers', $servers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('webloyer::servers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServerRequest\StoreRequest $request
     * @return Response
     */
    public function store(ServerRequest\StoreRequest $request, CreateServerService $service)
    {
        $input = $request->all();

        $serviceRequest = (new CreateServerRequest())
            ->setName($input['name'])
            ->setDescription($input['description'])
            ->setBody($input['body']);
        $service->execute($serviceRequest);

        return redirect()->route('servers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param ServerDomainModel\Server $server
     * @return Response
     */
    public function show(ServerDomainModel\Server $server)
    {
        return view('webloyer::servers.show')->with('server', $server);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ServerDomainModel\Server $server
     * @return Response
     */
    public function edit(ServerDomainModel\Server $server)
    {
        return view('webloyer::servers.edit')->with('server', $server);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ServerRequest\UpdateRequest $request
     * @param ServerDomainModel\Server    $server
     * @return Response
     */
    public function update(ServerRequest\UpdateRequest $request, ServerDomainModel\Server $server, UpdateServerService $service)
    {
        $input = $request->all();

        $serviceRequest = (new UpdateServerRequest())
            ->setId($server->id())
            ->setName($input['name'])
            ->setDescription($input['description'])
            ->setBody($input['body']);
        $service->execute($serviceRequest);

        return redirect()->route('servers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ServerDomainModel\Server $server
     * @return Response
     */
    public function destroy(ServerDomainModel\Server $server, DeleteServerService $service)
    {
        $serviceRequest = (new DeleteServerRequest())->setId($server->id());
        $service->execute($serviceRequest);

        return redirect()->route('servers.index');
    }
}
