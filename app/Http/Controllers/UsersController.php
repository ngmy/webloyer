<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\User\UserInterface;
use App\Repositories\Role\RoleInterface;
use App\Services\Form\User\UserForm;
use App\Models\User;

/**
 * Class UsersController
 * @package App\Http\Controllers
 */
class UsersController extends Controller
{

    /**
     * @var UserInterface
     */
    protected UserInterface $user;

    /**
     * @var UserForm
     */
    protected UserForm $userForm;

    /**
     * @var RoleInterface
     */
    protected RoleInterface $role;

    /**
     * UsersController constructor.
     * @param UserInterface $user
     * @param UserForm $userForm
     * @param RoleInterface $role
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
     * @param Request $request
     * @return Factory|View
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
     * @return Factory|View
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
     * @param Request $request
     * @return RedirectResponse
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
     * @param User $user
     * @return RedirectResponse
     */
    public function show(User $user)
    {
        return redirect()->route('users.edit', [$user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return Factory|View
     */
    public function edit(User $user)
    {
        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
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
     * @param User $user
     * @return Factory|View
     */
    public function changePassword(User $user)
    {
        return view('users.change_password')->with('user', $user);
    }

    /**
     * Update the password of the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
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
     * @param User $user
     * @return Factory|View
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
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
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
     * Show the form for editing the API token of the specified resource.
     *
     * @param User $user
     * @return Factory|View
     */
    public function editApiToken(User $user)
    {
        return view('users.edit_api_token')
            ->with('user', $user);
    }

    /**
     * Regenerate the API token of the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function regenerateApiToken(Request $request, User $user)
    {
        $input = array_merge($request->all(), ['id' => $user->id]);
        if ($this->userForm->regenerateApiToken($input)) {
            return redirect()->route('users.index');
        } else {
            return redirect()->route('users.api_token.edit', [$user])
                ->withInput()
                ->withErrors($this->userForm->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user)
    {
        $this->user->delete($user->id);
        return redirect()->route('users.index');
    }
}
