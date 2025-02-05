<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\API\V1\SecurityModule\UserController;
use App\Http\Controllers\API\V1\CourierModule\CourierController;
use App\Http\Controllers\API\V1\SettingModule\SettingController;
use App\Http\Controllers\API\V1\DocumentModule\DocumentController;
use App\Http\Controllers\API\V1\SecurityModule\PasswordResetController;
use App\Http\Controllers\API\V1\StatsController;
use App\Http\Controllers\API\V1\FirebaseNotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::post('/token/generate', [AuthController::class, 'generateToken']);

    Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
        Route::get('users/me', [UserController::class, 'myProfile']);

        Route::resource('users', UserController::class);

        Route::resource('firebase-notifications', FirebaseNotificationController::class);

        Route::get('/couriers/search', [CourierController::class, 'searchCouriers'])->name('couriers.search');

        Route::post('firebase-notifications/mark-all-as-read', [FirebaseNotificationController::class, 'markAllAsRead'])->name('firebaseNotifications.markAllAsRead');

        Route::post('firebase-notifications/delete-all-notifications', [FirebaseNotificationController::class, 'deleteAllNotifications'])->name('firebaseNotifications.deleteAllNotifications');

        Route::resource('couriers', CourierController::class);

        Route::get('/documents/all', [DocumentController::class, 'getDocuments'])->name('documents.all');

        Route::resource('documents', DocumentController::class);

        Route::post('documents/pass-turn', [DocumentController::class, 'passTurn']);

        Route::post('deliveries/finish', [CourierController::class, 'finishCourse']);

        Route::get('/settings/{keys}', [SettingController::class, 'getSettings']);

        Route::get('/stats/couriers', [StatsController::class, 'getCourierStats'])->name('stats.couriers');
        Route::get('/stats/documents', [StatsController::class, 'getDocumentStats'])->name('stats.documents');
    });

    Route::post('password/verify-user', [PasswordResetController::class, 'verifyUser']);

    Route::post('password/reset/verify-code', [PasswordResetController::class, 'verifyCode']);

    Route::post('password/reset', [PasswordResetController::class, 'resetPassword']);
});
