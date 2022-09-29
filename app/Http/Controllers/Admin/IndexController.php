<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Gate;

class IndexController extends AdminController
{


    public function  __construct()
    {

        parent::__construct();

        $this->template = config('settings.theme') . '.admin.index';
    }


    public function index()
    {
        if (Gate::denies('VIEW_ADMIN')) {
            abort(403);
        }

        //$this->template = config('settings.theme') . '.admin.index';
        $this->title = 'Панель администратора';

        return $this->renderOutput();
    }
}
