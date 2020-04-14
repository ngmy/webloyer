<?php

declare(strict_types=1);

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use App\Http\Requests\Server as ServerRequest;
use App\Models\Server;
use App\Repositories\Server\ServerInterface;
use App\Services\Form\Server\ServerForm;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    /** @var ServerInterface */
    private $server;
    /** @var ServerForm */
    private $serverForm;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Server\ServerInterface $server
     * @param \App\Services\Form\Server\ServerForm     $serverForm
     * @return void
     */
    public function __construct(ServerInterface $server, ServerForm $serverForm)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->server     = $server;
        $this->serverForm = $serverForm;
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

        $servers = $this->server->byPage($page, $perPage);

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

        $this->serverForm->save($input);

        return redirect()->route('servers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Server $server
     * @return Response
     */
    public function show(Server $server)
    {
        return view('servers.show')->with('server', $server);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Server $server
     * @return Response
     */
    public function edit(Server $server)
    {
        return view('servers.edit')->with('server', $server);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ServerRequest\UpdateRequest $request
     * @param \App\Models\Server          $server
     * @return Response
     */
    public function update(ServerRequest\UpdateRequest $request, Server $server)
    {
        $input = array_merge($request->all(), ['id' => $server->id]);

        $this->serverForm->update($input);

        return redirect()->route('servers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Server $server
     * @return Response
     */
    public function destroy(Server $server)
    {
        $this->server->delete($server->id);

        return redirect()->route('servers.index');
    }
}
