<?php

declare(strict_types=1);

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use App\Http\Requests\Server as ServerRequest;
use Webloyer\App\Server as ServerApplication;
use Webloyer\Domain\Model\Server as ServerDomainModel;

class ServerController extends Controller
{
    /** @var ServerApplication\ServerService */
    private $serverService;

    /**
     * Create a new controller instance.
     *
     * @param ServerApplication\ServerService $serverService
     * @return void
     */
    public function __construct(ServerApplication\ServerService $serverService)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->serverService = $serverService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param ServerRequest\IndexRequest $request
     * @return Response
     */
    public function index(ServerRequest\IndexRequest $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;

        $command = (new ServerApplication\Commands\GetServersCommand())
            ->setPage($page)
            ->setPerPage($perPage);

        $servers = $this->serverService->getServers($command);

        return view('servers.index')->with('servers', $servers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('servers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ServerRequest\StoreRequest $request
     * @return Response
     */
    public function store(ServerRequest\StoreRequest $request)
    {
        $input = $request->all();

        $command = (new ServerApplication\Commands\CreateServerCommand())
            ->setName($input['name'])
            ->setDescription($input['description'])
            ->setBody($input['body']);

        $this->serverService->createServer($command);

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
        return view('servers.show')->with('server', $server);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ServerDomainModel\Server $server
     * @return Response
     */
    public function edit(ServerDomainModel\Server $server)
    {
        return view('servers.edit')->with('server', $server);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ServerRequest\UpdateRequest $request
     * @param ServerDomainModel\Server    $server
     * @return Response
     */
    public function update(ServerRequest\UpdateRequest $request, ServerDomainModel\Server $server)
    {
        $input = $request->all();

        $command = (new ServerApplication\Commands\UpdateServerCommand())
            ->setId($server->id())
            ->setName($input['name'])
            ->setDescription($input['description'])
            ->setBody($input['body']);

        $this->serverService->updateServer($command);

        return redirect()->route('servers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ServerDomainModel\Server $server
     * @return Response
     */
    public function destroy(ServerDomainModel\Server $server)
    {
        $command = (new ServerApplication\Commands\DeleteServerCommand())->setId($server->id());

        $this->serverService->deleteServer($command);

        return redirect()->route('servers.index');
    }
}
