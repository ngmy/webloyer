<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User as UserRequest;
use App\Models\User;
use App\Repositories\Role\RoleInterface;
use App\Repositories\User\UserInterface;
use App\Services\Form\User\UserForm;

class UserController extends Controller
{
    /** @var UserInterface */
    private $user;
    /** @var UserForm */
    private $userForm;
    /** @var RoleInterface */
    private $role;

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
     * @param UserRequest\IndexRequest $request
     * @return Response
     */
    public function index(UserRequest\IndexRequest $request)
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
     * @param UserRequest\StoreRequest $request
     * @return Response
     */
    public function store(UserRequest\StoreRequest $request)
    {
        $input = $request->all();

        $this->userForm->save($input);

        return redirect()->route('users.index');
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
     * @param UserRequest\UpdateRequest $request
     * @param \App\Models\User         $user
     * @return Response
     */
    public function update(UserRequest\UpdateRequest $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        $this->userForm->update($input);

        return redirect()->route('users.index');
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
     * @param UserRequest\UpdatePasswordRequest $request
     * @param \App\Models\User         $user
     * @return Response
     */
    public function updatePassword(UserRequest\UpdatePasswordRequest $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        $this->userForm->updatePassword($input);

        return redirect()->route('users.index');
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
     * @param UserRequest\UpdateRoleRequest $request
     * @param \App\Models\User         $user
     * @return Response
     */
    public function updateRole(UserRequest\UpdateRoleRequest $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        $this->userForm->updateRole($input);

        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the API token of the specified resource.
     *
     * @param \App\Models\User $user
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
     * @param UserRequest\RegenerateApiTokenRequest $request
     * @param \App\Models\User         $user
     * @return Response
     */
    public function regenerateApiToken(UserRequest\RegenerateApiTokenRequest $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        $this->userForm->regenerateApiToken($input);

        return redirect()->route('users.index');
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
