<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm\UserForm;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\IdentityAccess\Application\Role\RoleService;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;

class UsersController extends Controller
{
    private $userForm;

    private $userService;

    private $roleService;

    /**
     * Create a new controller instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm\UserForm $userForm
     * @param \Ngmy\Webloyer\IdentityAccess\Application\User\UserService  $userService
     * @param \Ngmy\Webloyer\IdentityAccess\Application\Role\RoleService  $roleService
     * @return void
     */
    public function __construct(UserForm $userForm, UserService $userService, RoleService $roleService)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->userForm = $userForm;
        $this->userService = $userService;
        $this->roleService = $roleService;
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

        $users = $this->userService->getUsersByPage($page, $perPage);

        return view('users.index')->with('users', $users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $roles = $this->roleService->getAllRoles();

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
     * @param \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user
     * @return Response
     */
    public function show(User $user)
    {
        return redirect()->route('users.edit', [$user->userId()->id()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user
     * @return Response
     */
    public function edit(User $user)
    {
        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user
     * @return Response
     */
    public function update(Request $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->userId()->id()]);

        if ($this->userForm->update($input)) {
            return redirect()->route('users.index');
        } else {
            return redirect()->route('users.edit', [$user->userId()->id()])
                ->withInput()
                ->withErrors($this->userForm->errors());
        }
    }

    /**
     * Show the form for changing the password of the specified resource.
     *
     * @param \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user
     * @return Response
     */
    public function changePassword(User $user)
    {
        return view('users.change_password')->with('user', $user);
    }

    /**
     * Update the password of the specified resource in storage.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user
     * @return Response
     */
    public function updatePassword(Request $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->userId()->id()]);

        if ($this->userForm->updatePassword($input)) {
            return redirect()->route('users.index');
        } else {
            return redirect()->route('users.password.change', [$user->userId()->id()])
                ->withInput()
                ->withErrors($this->userForm->errors());
        }
    }

    /**
     * Show the form for editing the role of the specified resource.
     *
     * @param \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user
     * @return Response
     */
    public function editRole(User $user)
    {
        $roles = $this->roleService->getAllRoles();

        return view('users.edit_role')
            ->with('user', $user)
            ->with('roles', $roles);
    }

    /**
     * Update the role of the specified resource in storage.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user
     * @return Response
     */
    public function updateRole(Request $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->userId()->id()]);

        if ($this->userForm->updateRole($input)) {
            return redirect()->route('users.index');
        } else {
            return redirect()->route('users.role.edit', [$user->userId()->id()])
                ->withInput()
                ->withErrors($this->userForm->errors());
        }
    }

    /**
     * Show the form for editing the API token of the specified resource.
     *
     * @param \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user
     * @return Response
     */
    public function editApiToken(User $user)
    {
        return view('users.edit_api_token')
            ->with('user', $user);
    }

    /**
     * Regenerate the API token of the specified resource in storage.
     *
     * @param \Illuminate\Http\Request                             $request
     * @param \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user
     * @return Response
     */
    public function regenerateApiToken(Request $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->userId()->id()]);

        if ($this->userForm->regenerateApiToken($input)) {
            return redirect()->route('users.index');
        } else {
            return redirect()->route('users.api_token.edit', [$user->userId()->id()])
                ->withInput()
                ->withErrors($this->userForm->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $this->userService->removeUser($user->userId()->id());

        return redirect()->route('users.index');
    }
}
