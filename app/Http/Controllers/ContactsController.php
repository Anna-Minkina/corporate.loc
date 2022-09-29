<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MenusRepository;
use App\Models\Menu;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Arr;
use App\Mail\MailShipped;

class ContactsController extends SiteController
{
    //
    public function __construct()
    {

        parent::__construct(new MenusRepository(new Menu()));

        $this->bar = 'left';
        $this->template = config('settings.theme') . '.contacts';
    }

    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $messages = [
                'required' => "Поле :attribute обязательно к заполнению",
                'email' => "Поле :attribute должно соответствовать email адресу"
            ];
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'text' => 'required'
            ], $messages);


            Mail::to(env('MAIL_REPLY_TO'))
                ->send(new MailShipped($request));

            $request->session()->flash('status', 'Email is sent');
            return redirect()->route('contacts');
        }

        $this->title = 'Контакты';

        $content = view(config('settings.theme') . '.contact_content')->render();
        $this->vars = Arr::add($this->vars, 'content', $content);

        $this->contentLeftBar =
            view(config('settings.theme') . '.contact_bar')->render();

        return $this->renderOutput();
    }
}
