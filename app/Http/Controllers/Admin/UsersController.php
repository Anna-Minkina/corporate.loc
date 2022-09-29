<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;
use App\Models\Role;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Gate;

class UsersController extends AdminController
{
    protected $us_rep;
    protected $rol_rep;

    public function __construct(RolesRepository $rol_rep, UsersRepository $us_rep)
    {
        parent::__construct();

        $this->us_rep = $us_rep;
        $this->rol_rep = $rol_rep;

        $this->template = config('settings.theme') . '.admin.users';
    }
    public function index()
    {
        if (Gate::denies('EDIT_USERS')) {
            abort(403);
        }

        $users = $this->us_rep->get();

        $this->content = view(config('settings.theme') . '.admin.users_content')->with(['users' => $users])->render();

        return $this->renderOutput();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->title = 'Новый пользователь';

        $roles = $this->getRoles()->reduce(function ($returnRoles, $role) {
            $returnRoles[$role->id] = $role->name;
            return $returnRoles;
        }, []);
        $this->content = view(config('settings.theme') . '.admin.users_create_content')->with('roles', $roles)->render();
        return $this->renderOutput();
    }

    public function getRoles()
    {
        return Role::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $result = $this->us_rep->addUser($request);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return redirect('/admin')->with($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->title = 'Изменение пользователя - ' . $user->name;

        $roles = $this->getRoles()->reduce(function ($returnRoles, $role) {
            $returnRoles[$role->id] = $role->name;
            return $returnRoles;
        }, []);
        $this->content = view(config('settings.theme') . '.admin.users_create_content')->with(['roles' => $roles, 'user' => $user])->render();
        return $this->renderOutput();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $result = $this->us_rep->updateUser($request, $user);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return redirect('/admin')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $result = $this->us_rep->deleteUser($user);
        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }
        return redirect('/admin')->with($result);
    }
}
