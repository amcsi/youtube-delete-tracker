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

use App\Http\Controllers\HomeController;
use App\Http\Controllers\YoutubeLoginController;

Route::get('/', [HomeController::class, 'index']);

Route::get(
    '/guest',
    function () {
        return view('guest');
    }
)->name('guest');

Route::get('/youtube-login', [YoutubeLoginController::class, 'login'])->name('youtube-login');
Route::get('/callback', [YoutubeLoginController::class, 'callback']);

Route::get(
    '/privacy-policy',
    function () {
        return view('privacyPolicy');
    }
);

Route::middleware(['auth:sanctum', 'verified'])->get(
    '/dashboard',
    function () {
        return view('dashboard');
    }
)->name('dashboard');
