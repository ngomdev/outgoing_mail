<?php

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use App\Filament\Pages\PasswordExpired;
use App\Filament\Pages\Auth\SetPassword;
use App\Http\Middleware\PasswordNotExpired;
use App\Http\Controllers\DocumentTemplateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

if (env('APP_ENV') === 'production') {
    URL::forceScheme('https');
}


Route::get('preview-doc', function () {
    return view('preview-doc');
});


Route::get('/test', function () {

    return User::firstWhere('email', 'respSuivi@yopmail.com')->signing_code;
    return User::all();

    return phpinfo();
});


Route::get('password-expired', PasswordExpired::class)->middleware(['auth', PasswordNotExpired::class])->name('password.expired');

// Route::get('/password/reset', ResetPassword::class)->name('password.reset');
Route::get('/set-password', SetPassword::class)->name('password.set');

Route::get('get-document', [DocumentTemplateController::class, 'getDocument'])->middleware(['auth'])->name('document.get');

Route::get('/download-doc', function () {
    $file = public_path('storage/doc-attachments/contract-Orson-Copeland-v01.pdf');

    return response()->download($file);
})->name('download.contract');
