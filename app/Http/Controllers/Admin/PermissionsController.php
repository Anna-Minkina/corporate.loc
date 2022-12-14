<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\PermissionsRepository;
use App\Repositories\RolesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PermissionsController extends AdminController
{
    protected $per_rep;
    protected $rol_rep;

    public function __construct(PermissionsRepository $per_rep, RolesRepository $rol_rep)
    {
        parent::__construct();

        $this->per_rep = $per_rep;
        $this->rol_rep = $rol_rep;

        $this->template = config('settings.theme') . '.admin.permissions';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Gate::denies('EDIT_USERS')) {
            abort(403);
        }

        $this->title = "Менеджер прав пользователей";

        $roles = $this->getRoles();

        $permissions = $this->getPermissions();

        $this->content = view(config('settings.theme') . '.admin.permissions_content')->with(['roles' => $roles, 'priv' => $permissions])->render();

        return $this->renderOutput();
    }

    public function getRoles()
    {

        $roles = $this->rol_rep->get();

        return $roles;
    }

    public function getPermissions()
    {

        $permissions = $this->per_rep->get();

        return $permissions;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->per_rep->changePermissions($request);

        if (is_array($result) && !empty($result['error'])) {
            return back()->with($result);
        }

        return back()->with($result);
    }
}
