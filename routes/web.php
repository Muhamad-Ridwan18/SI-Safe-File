<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\FileEncryptionController;
use App\Http\Controllers\RSAController;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/register', function ()  {
    return view('auth.register');    
});

Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });


    Route::get('/', [App\Http\Controllers\HomeController::class, 'dashboard'])->name('dashboard');
    Route::resource('user', App\Http\Controllers\UserController::class);
    Route::post('user/delete', 'App\Http\Controllers\UserController@delete')->name('user.delete');

    Route::resource('documents', App\Http\Controllers\DocumentController::class);
    Route::post('documents/delete', 'App\Http\Controllers\DocumentController@delete')->name('documents.delete');
    Route::get('documents/share/{id}', [App\Http\Controllers\DocumentController::class, 'share'])->name('documents.share');
    Route::get('decrypt-documents', [App\Http\Controllers\DocumentController::class, 'showDecrypt'])->name('decrypt.index');
    Route::get('test-documents', [App\Http\Controllers\DocumentController::class, 'test'])->name('test.index');
    Route::get('/documents/download/{id}', [App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');
    Route::post('documents/encrypt', [App\Http\Controllers\DocumentController::class, 'encrypt'])->name('documents.encrypt');
    Route::post('documents/decrypt', [App\Http\Controllers\DocumentController::class, 'decrypt'])->name('documents.decrypt');
    Route::post('documents/test_avalanche/{id}', [App\Http\Controllers\DocumentController::class, 'testAvalancheEffect'])->name('documents.test_avalanche');

});