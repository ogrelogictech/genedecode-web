<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\AuthController;
use App\Http\Controllers\Site\AccountController;
use App\Http\Controllers\Site\VideoProgressController;
use App\Http\Controllers\Site\WatchController;

Route::get('/', function () {
    return view('site.home');
});

Route::get('/gift', function () {
    return view('site.gift');
});

Route::get('/about', function () {
    return view('site.about');
});

Route::get('/schedule', function () {
    return view('site.schedule');
});

Route::get('/surface-area', function () {
    return view('site.surface-area');
});

Route::get('/interviews', function () {
    return view('site.interviews');
});

Route::get('/deep-dives', function () {
    return view('site.deep-dives');
});

Route::get('/community', function () {
    return view('site.community');
});

Route::get('/donate', function () {
    return view('site.donate');
});

Route::get('/faq', function () {
    return view('site.faq');
});

Route::get('/join-us', function () {
    return view('site.join-us');
});

Route::get('/account', function () {
    return view('site.account');
})->middleware('auth');
Route::put('/account/profile', [AccountController::class, 'updateProfile'])->middleware('auth')->name('account.profile.update');
Route::put('/account/publicprofile', [AccountController::class, 'updatePublicProfile'])->middleware('auth')->name('account.publicprofile.update');
Route::put('/account/password', [AccountController::class, 'updatePassword'])->middleware('auth')->name('account.password.update');

Route::get('/contact', function () {
    return view('site.contact');
});

Route::get('/live', function () {
    return view('site.live');
});

Route::get('/privacy', function () {
    return view('site.privacy');
});

Route::get('/terms', function () {
    return view('site.terms');
});

Route::get('/subscriber-agreement', function () {
    return view('site.subscriber-agreement');
});

// Route::get('/watch', function () {
//     return view('site.watch');
// });

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest')
    ->name('register.store');

Route::get('/login', function () {
    return view('site.auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', function () {
    return view('site.auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
    ->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('site.auth.reset-password', [
        'token' => $token,
        'email' => request('email'),
    ]);
})->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');

Route::get('/video-progress', [VideoProgressController::class, 'index'])
    ->middleware('auth')
    ->name('video.progress.index');
    
Route::post('/video-progress', [VideoProgressController::class, 'store'])
    ->middleware('auth')
    ->name('video.progress.store');

Route::get('/video-progress/{videoId}', [VideoProgressController::class, 'show'])
    ->middleware('auth')
    ->name('video.progress.show');


Route::middleware(['auth'])->group(function () {
    Route::get('/watch/{videoId?}', [WatchController::class, 'show'])->name('watch');
});


    