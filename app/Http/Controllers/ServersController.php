<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ngmy\Webloyer\Webloyer\Application\Server\ServerService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ServerForm\ServerForm;

class ServersController extends Controller
{
    private $serverForm;

    private $serverService;

    /**
     * Create a new controller instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ServerForm\ServerForm $serverForm
     * @param \Ngmy\Webloyer\Webloyer\Application\Server\ServerService        $serverService
     * @return void
     */
    public function __construct(ServerForm $serverForm, ServerService $serverService)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->serverForm = $serverForm;
        $this->serverService = $serverService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);

        $perPage = 10;

        $servers = $this->serverService->getServersByPage($page, $perPage);

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
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        if ($this->serverForm->save($input)) {
            return redirect()->route('servers.index');
        } else {
            return redirect()->route('servers.create')
                ->withInput()
                ->withErrors($this->serverForm->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server $server
     * @return Response
     */
    public function show(Server $server)
    {
        return view('servers.show')->with('server', $server);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server $server
     * @return Response
     */
    public function edit(Server $server)
    {
        return view('servers.edit')->with('server', $server);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request                           $request
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server $server
     * @return Response
     */
    public function update(Request $request, Server $server)
    {
        $input = array_merge($request->all(), ['id' => $server->serverId()->id()]);

        if ($this->serverForm->update($input)) {
            return redirect()->route('servers.index');
        } else {
            return redirect()->route('servers.edit', [$server])
                ->withInput()
                ->withErrors($this->serverForm->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server $server
     * @return Response
     */
    public function destroy(Server $server)
    {
        $this->serverService->removeServer($server->serverId()->id());

        return redirect()->route('servers.index');
    }
}
