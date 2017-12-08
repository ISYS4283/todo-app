<?php

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

Route::group(['middleware' => ['auth']], function () {
    Route::view('/', 'welcome');
    Route::post('/', 'GraderController@grade');
});

Route::name('login')->get('/login', '\\'.Route::getRoutes()->getByName('shibboleth-login')->getActionName());
