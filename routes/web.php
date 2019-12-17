<?php


use Goutte\Client;
use Illuminate\Support\Facades\Route;

Route::get('/', 'DashboardController@index')->name('home');
Route::get('/jsonconverter', 'JsonController@index')->name('json.index');
Route::post('/jsonconverter/download', 'JsonController@create')->name('json.download');
Route::post('/jsonconverter', 'JsonController@table')->name('json.get');
Route::get('/pamuk', 'JsonController@response')->name('json.response');
Route::get('/responsephp', 'JsonController@responsephp')->name('json.response');
