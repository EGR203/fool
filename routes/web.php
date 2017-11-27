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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/vk', 'VkController@index')->name('vkbot');

Route::post('/callback/{name}', 'ProxyController@doProxy');
Route::post('/proxy/reg/{name}', 'ProxyController@register');
