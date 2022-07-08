<?php

use App\Http\Controllers\ReferralController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::redirect('/', 'referrals');
Route::group(['prefix' => 'referrals', 'middleware' =>['auth', 'verified']], function() {
    Route::get('/', [ReferralController::class, 'index'])->name('referrals');
    Route::post('/invite', [ReferralController::class, 'invite'])->name('referrals.invite');
});

require __DIR__.'/auth.php';
