<?php

use App\Http\Controllers\AdminMembershipController;
use App\Http\Controllers\MembershipApplicationController;
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
    Route::get('membership/apply', [MembershipApplicationController::class, 'show'])->name('membership.application');
    Route::post('membership/apply', [MembershipApplicationController::class, 'store'])->middleware('throttle:10,1')->name('membership.application.store');
    Route::post('membership/slip', [MembershipApplicationController::class, 'uploadSlip'])->middleware('throttle:10,1')->name('membership.slip.store');
    Route::get('membership/slip', [MembershipApplicationController::class, 'downloadSlip'])->name('membership.slip.download');
});

Route::middleware(['auth', 'verified', 'can:access-admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminMembershipController::class, 'dashboard'])->name('dashboard');
    Route::get('memberships', [AdminMembershipController::class, 'index'])->name('memberships.index');
    Route::get('memberships/{application}', [AdminMembershipController::class, 'show'])->name('memberships.show');
    Route::get('memberships/{application}/slip', [AdminMembershipController::class, 'slip'])->name('memberships.slip');
});

require __DIR__.'/settings.php';
