<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Http\Controllers\SiteController;
use App\Models\Menu;
use App\Repositories\MenusRepository;
use Exception;



class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported. Список исключений, которые не логгируются
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


       /**
     * Render an exception into an HTTP response. Визуализировать исключение в HTTP-ответ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        if ($this->isHttpException($e)) {//возможно нужно подправить по современному учебнику стр 654
            $statusCode = $e->getStatusCode();

            switch ($statusCode) {
                case '404':

                    $obj = new SiteController(new MenusRepository(new Menu())); //формируем меню из sitecontroller
                    // в $obj располагается объект родительского контролера SiteController
                    //dd($obj);
                    $navigation = view(config('settings.theme') . '.navigation')->with('menu',$obj->getMenu())->render();

                    \Log::alert('Страница не найдена - '.$request->url());

                    return response()->view(config('settings.theme') . '.404',['bar'=>'no','title'=>'Страница не найдена','navigation'=>$navigation]);
            }
        }
        return parent::render($request, $e);
    }
}
