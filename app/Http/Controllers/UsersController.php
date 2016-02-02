<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\User\UserInterface;
use App\Repositories\Role\RoleInterface;
use App\Services\Form\User\UserForm;
use App\Models\User;

class UsersController extends Controller
{
    protected $user;

    protected $userForm;

    protected $role;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\User\UserInterface $user
     * @param \App\Services\Form\User\UserForm     $userForm
     * @param \App\Repositories\Role\RoleInterface $role
     * @return void
     */
    public function __construct(UserInterface $user, UserForm $userForm, RoleInterface $role)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->user     = $user;
        $this->userForm = $userForm;
        $this->role     = $role;
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

        $users = $this->user->byPage($page, $perPage);

        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = $this->role->all();

        return view('users.create')
            ->with('roles', $roles);
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

        if ($this->userForm->save($input)) {
            return redirect()->route('users.index');
        } else {
            return redirect()->route('users.create')
                ->withInput()
                ->withErrors($this->userForm->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\User $user
     * @return Response
     */
    public function show(User $user)
    {
        return redirect()->route('users.edit', [$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\User $user
     * @return Response
     */
    public function edit(User $user)
    {
        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return Response
     */
    public function update(Request $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        if ($this->userForm->update($input)) {
            return redirect()->route('users.index');
        } else {
            return redirect()->route('users.edit', [$user])
                ->withInput()
                ->withErrors($this->userForm->errors());
        }
    }

    /**
     * Show the form for changing the password of the specified resource.
     *
     * @param \App\Models\User $user
     * @return Response
     */
    public function changePassword(User $user)
    {
        return view('users.change_password')->with('user', $user);
    }

    /**
     * Update the password of the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return Response
     */
    public function updatePassword(Request $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        if ($this->userForm->updatePassword($input)) {
            return redirect()->route('users.index');
        } else {
            return redirect()->route('users.password.change', [$user])
                ->withInput()
                ->withErrors($this->userForm->errors());
        }
    }

    /**
     * Show the form for editing the role of the specified resource.
     *
     * @param \App\Models\User $user
     * @return Response
     */
    public function editRole(User $user)
    {
        $roles = $this->role->all();

        return view('users.edit_role')
            ->with('user', $user)
            ->with('roles', $roles);
    }

    /**
     * Update the role of the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return Response
     */
    public function updateRole(Request $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        if ($this->userForm->updateRole($input)) {
            return redirect()->route('users.index');
        } else {
            return redirect()->route('users.role.edit', [$user])
                ->withInput()
                ->withErrors($this->userForm->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $this->user->delete($user->id);

        return redirect()->route('users.index');
    }
}
