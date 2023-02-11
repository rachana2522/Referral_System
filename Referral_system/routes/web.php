<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
    return redirect('/login');
});
    Route::group(['middleware' => []], function () {

    Route::get('/register', [UserController::class, 'loadRegister']);

    Route::post('/user_registered', [UserController::class, 'registered'])->name('registered');

    Route::get('/referral-register', [UserController::class, 'loadReferralRegister']);
    Route::get('/email-verification/{token}', [UserController::class, 'emailVerification']);


    Route::get('/login', [UserController::class, 'loadlogin']);

    Route::post('/login', [UserController::class, 'userlogin'])->name('login');
});


    Route::group(['middleware' => []], function () {

    

    Route::get('/logout', [UserController::class, 'logout'])-> name('logout');

    Route::get('/referralTrack', [UserController::class, 'referralTrack'])-> name('referralTrack');
    
    Route::get('/delete-account', [UserController::class, 'deleteAccount'])-> name('deleteAccount');

});

Route::get('/dashboard', [UserController::class, 'loadDashboard']);

