<?php

use Illuminate\Support\Facades\Artisan;
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

Route::get('/', [\App\Http\Controllers\IndexController::class, 'index'])->name('index');
//Route::get('{any}', [\App\Http\Controllers\IndexController::class, 'index'])->where('any', '.*')->name('index');

//Route::get('/artisan', function(){
//    Artisan::call('key:generate');
//    Artisan::call('storage:link');
//    Artisan::call('jwt:secret');
//});

