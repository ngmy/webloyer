<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User as UserRequest;
use App\Repositories\Role\RoleInterface;
use Webloyer\App\User as UserApplication;
use Webloyer\Domain\Model\User as UserDomainModel;

class UserController extends Controller
{
    /** @var UserApplication */
    private $userService;
    /** @var RoleInterface */
    private $role;

    /**
     * Create a new controller instance.
     *
     * @param UserApplication\UserService $userService
     * @param \App\Repositories\Role\RoleInterface $role
     * @return void
     */
    public function __construct(UserApplication\UserService $userService, RoleInterface $role)
    {
        $this->middleware('auth');
        $this->middleware('acl');

        $this->userService = $userService;
        $this->role = $role;
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

        $users = $this->userService->byPage($page, $perPage);

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

        $this->userService->save($input);

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param UserDomainModel\User $user
     * @return Response
     */
    public function show(UserDomainModel\User $user)
    {
        return redirect()->route('users.edit', [$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserDomainModel\User $user
     * @return Response
     */
    public function edit(UserDomainMode\User $user)
    {
        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest\UpdateRequest $request
     * @param UserDomainModel\User      $user
     * @return Response
     */
    public function update(UserRequest\UpdateRequest $request, UserDomainModel\User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        $this->userService->update($input);

        return redirect()->route('users.index');
    }

    /**
     * Show the form for changing the password of the specified resource.
     *
     * @param UserDomainModel\User $user
     * @return Response
     */
    public function changePassword(UserDomainMode\User $user)
    {
        return view('users.change_password')->with('user', $user);
    }

    /**
     * Update the password of the specified resource in storage.
     *
     * @param UserRequest\UpdatePasswordRequest $request
     * @param UserDomainModel\User              $user
     * @return Response
     */
    public function updatePassword(UserRequest\UpdatePasswordRequest $request, UserDomainModel\User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        $this->userService->updatePassword($input);

        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the role of the specified resource.
     *
     * @param UserDomainModel\User $user
     * @return Response
     */
    public function editRole(UserDomainModel\User $user)
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
     * @param UserDomainModel\User          $user
     * @return Response
     */
    public function updateRole(UserRequest\UpdateRoleRequest $request, UserDomainModel\User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        $this->userService->updateRole($input);

        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the API token of the specified resource.
     *
     * @param UserDomainModel\User $user
     * @return Response
     */
    public function editApiToken(UserDomainModel\User $user)
    {
        return view('users.edit_api_token')
            ->with('user', $user);
    }

    /**
     * Regenerate the API token of the specified resource in storage.
     *
     * @param UserRequest\RegenerateApiTokenRequest $request
     * @param UserDomainModel\User                  $user
     * @return Response
     */
    public function regenerateApiToken(UserRequest\RegenerateApiTokenRequest $request, UserDomainModel\User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);

        $this->userService->regenerateApiToken($input);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param UserDomainModel\User $user
     * @return Response
     */
    public function destroy(UserDomainModel\User $user)
    {
        $this->userService->delete($user->id);

        return redirect()->route('users.index');
    }
}
