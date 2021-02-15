<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
//Top level Route
Route::get('/', function () {
    return view('login');
});

//Authentication Routes
Route::get('login', function () {
    if (Auth::check()) {
        return view('filemanager');
    }
    return view('login');
})->name('login');
Route::get('register', function () {
    if (Auth::check()) {
        return view('filemanager');
    }
    return view('register');
});
Route::post('registerEmail', [App\Http\Controllers\LoginController::class, 'registerEmail']);
Route::post('emailLogin', [App\Http\Controllers\LoginController::class, 'emailLogin']);
Route::post('userLogout', [App\Http\Controllers\LoginController::class, 'userLogout']);

//File Manager Routes
Route::get('fileManager',[App\Http\Controllers\FileController::class, 'listFiles']);
Route::post('uploadFiles',[App\Http\Controllers\FileController::class, 'uploadFiles']);
Route::post('downloadFiles',[App\Http\Controllers\FileController::class, 'downloadFiles']);
Route::post('deleteFiles',[App\Http\Controllers\FileController::class, 'deleteFiles']);
