<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/about', 'about')->name('about');
Route::view('/news', 'news')->name('news');
Route::view('/events', 'events')->name('events');
Route::view('/membership', 'membership')->name('membership');
Route::view('/gallery', 'gallery')->name('gallery');
Route::view('/support', 'support')->name('support');
Route::view('/documents', 'documents')->name('documents');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
