<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('site.home');
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
});

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

Route::get('/watch', function () {
    return view('site.watch');
});