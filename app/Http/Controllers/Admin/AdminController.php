<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Arr;
use Menu;
use Gate;


class AdminController extends Controller
{
    protected $p_rep;

    protected $a_rep;

    protected $user;

    protected $template;

    protected $content = FALSE;

    protected $title;

    protected $vars;

    public function __construct()
    {

        $this->user = Auth::user();
    }



    public function renderOutput()
    {

        $this->vars = Arr::add($this->vars, 'title', $this->title);

        $menu = $this->getMenu();

        $navigation = view(config('settings.theme') . '.admin.navigation')->with('menu', $menu)->render();
        $this->vars = Arr::add($this->vars, 'navigation', $navigation);

        if ($this->content) {

            $this->vars = Arr::add($this->vars, 'content', $this->content);
        }

        $footer = view(config('settings.theme') . '.admin.footer')->render();
        $this->vars = Arr::add($this->vars, 'footer', $footer);

        return view($this->template)->with($this->vars);
    }

    public function getMenu()
    {

        return Menu::make('adminMenu', function ($menu) {
            if (Gate::allows('VIEW_ADMIN_ARTICLES')) {
                $menu->add('Статьи', ['action' => 'Admin\ArticlesController@index']);
            }
            if (Gate::allows('VIEW_ADMIN_MENU')) {
                $menu->add('Меню', ['action' => 'Admin\MenusController@index']);
            }
            if (Gate::allows('EDIT_USERS')) {
                $menu->add('Пользователи', ['action' => 'Admin\UsersController@index']);
            }
            if (Gate::allows('EDIT_USERS')) {
                $menu->add('Привелегии', ['action' => 'Admin\PermissionsController@index']);
            }
        });
    }
}
