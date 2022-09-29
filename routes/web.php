<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('/', IndexController::class)->only(['index'])->names(['index' => 'home']);

Route::resource('portfolios', PortfolioController::class)->parameters(['portfolios' => 'alias']);

Route::resource('articles', ArticlesController::class)->parameters(['articles' => 'alias']);

Route::get('articles/cat/{cat_alias?}', 'ArticlesController@index')->name('articlesCat')->where('cat_alias', '[\w-]+'); //[\w-]+  последовательность из букв, цифр, _ и - может повторяться любое множество раз

Route::resource('comment', CommentController::class)->only(['store']);

Route::match(['GET', 'POST'], 'contacts', 'ContactsController@index')->name('contacts');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');

Route::post('login', 'Auth\LoginController@login');

Route::get('logout', 'Auth\LoginController@logout');



Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/', 'Admin\IndexController@index')->name('adminIndex');

    Route::resource('/articles', Admin\ArticlesController::class)->only(['index', 'create', 'edit', 'destroy', 'update', 'store']);

    Route::resource('/permissions', Admin\PermissionsController::class);

    Route::resource('/users', Admin\UsersController::class);

    Route::resource('/menus', Admin\MenusController::class);
});
