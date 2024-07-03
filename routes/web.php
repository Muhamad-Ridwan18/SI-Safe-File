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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::resource('user', App\Http\Controllers\UserController::class);
    Route::post('user/delete', 'App\Http\Controllers\UserController@delete')->name('user.delete');

    Route::resource('dokumen', App\Http\Controllers\DokumenController::class);
    Route::post('dokumen/delete', 'App\Http\Controllers\DokumenController@delete')->name('dokumen.delete');

    Route::resource('documents', App\Http\Controllers\DocumentController::class);
    Route::post('documents/delete', 'App\Http\Controllers\DocumentController@delete')->name('documents.delete');
    Route::get('documents/share/{id}', [App\Http\Controllers\DocumentController::class, 'share'])->name('documents.share');
    Route::post('documents/share', [App\Http\Controllers\DocumentController::class, 'shareDocument'])->name('documents.shareDocument');
    Route::get('/documents/{document}/scan', [App\Http\Controllers\DocumentController::class, 'showScanPage'])->name('documents.scan');
    Route::post('/documents/{document}/access', [App\Http\Controllers\DocumentController::class, 'accessDocument'])->name('documents.access');

    Route::resource('access_codes', App\Http\Controllers\AccessCodeController::class);
    Route::get('access_codes/create/{id}', [App\Http\Controllers\AccessCodeController::class, 'create'])->name('access_codes.create');
    Route::post('/documents/{document}/download-qrcode', [App\Http\Controllers\AccessCodeController::class,'downloadQrCode'])->name('documents.download-qrcode');

    Route::resource('shared_documents', App\Http\Controllers\SharedDocumentController::class);
    Route::post('/documents/{document}/request-qrcode', [App\Http\Controllers\SharedDocumentController::class, 'requestQrCode'])->name('documents.request-qrcode');
    Route::post('/access-requests/{accessRequest}/approve', [App\Http\Controllers\SharedDocumentController::class, 'approveQrCode'])->name('access-requests.approve');
    Route::post('/access-requests/{accessRequest}/deny', [App\Http\Controllers\SharedDocumentController::class, 'denyQrCode'])->name('access-requests.deny');

    
    Route::resource('access-requests', App\Http\Controllers\AccessRequestController::class);


});