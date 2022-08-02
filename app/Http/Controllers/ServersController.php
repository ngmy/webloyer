<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\Server\ServerInterface;
use App\Services\Form\Server\ServerForm;
use App\Models\Server;

/**
 * Class ServersController
 * @package App\Http\Controllers
 */
class ServersController extends Controller
{
    /**
     * @var ServerInterface
     */
    protected ServerInterface $server;

    /**
     * @var ServerForm
     */
    protected ServerForm $serverForm;

    /**
     * ServersController constructor.
     * @param ServerInterface $server
     * @param ServerForm $serverForm
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
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 10;
        $servers = $this->server->byPage($page, $perPage);
        return view('servers.index')->with('servers', $servers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('servers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
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
     * @param Server $server
     * @return Factory|View
     */
    public function show(Server $server)
    {
        return view('servers.show')->with('server', $server);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Server $server
     * @return Factory|View
     */
    public function edit(Server $server)
    {
        return view('servers.edit')->with('server', $server);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Server $server
     * @return RedirectResponse
     */
    public function update(Request $request, Server $server)
    {
        $input = array_merge($request->all(), ['id' => $server->id]);
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
     * @param Server $server
     * @return RedirectResponse
     */
    public function destroy(Server $server)
    {
        $this->server->delete($server->id);
        return redirect()->route('servers.index');
    }
}
