<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Admin\WeightController;
use App\Http\Controllers\CafeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SmartSearchController;

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

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('resent', true);
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// Rute untuk menangani link verifikasi yang dikirim ke email
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    // Temukan user berdasarkan ID
    $user = \App\Models\User::findOrFail($id);
    
    // Verifikasi hash
    $hash_check = sha1($user->getEmailForVerification());
    if (! hash_equals($hash_check, $hash)) {
        throw new \Illuminate\Auth\AuthenticationException;
    }
    
    // Jika belum diverifikasi, update kolom email_verified_at
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }
    
    // Login user
    \Illuminate\Support\Facades\Auth::login($user);
    
    return redirect('/')->with('status', 'Email berhasil diverifikasi!');
})->middleware('signed')->name('verification.verify');

// Home Route
Route::get('/home', function () {
    return redirect('/');
})->name('home');

// Cafe Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/search-cafe', [App\Http\Controllers\SmartSearchController::class, 'index'])->name('search-cafe');
    Route::get('/smart-search', [App\Http\Controllers\SmartSearchController::class, 'search'])->name('cafe.smart-search');
    Route::get('/search-history', [App\Http\Controllers\SmartSearchController::class, 'history'])->name('cafe.search-history');
    Route::get('/search-history/{id}', [App\Http\Controllers\SmartSearchController::class, 'detail'])->name('cafe.smart-search-detail');
    Route::get('/recommend-cafe', [App\Http\Controllers\CafeController::class, 'recommend'])->name('recommend-cafe');
    Route::get('/all-cafes', [App\Http\Controllers\CafeController::class, 'index'])->name('all-cafes');
    Route::get('/cafes/{id}', [App\Http\Controllers\CafeController::class, 'show'])->name('cafes.show');
    
    // Favorite Routes
    Route::post('/cafes/{id}/favorite', [FavoriteController::class, 'toggleFavorite'])->name('cafes.favorite.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index')->middleware('auth');
});

// Google Login
Route::get('login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);

// Profil dan Pengaturan Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});

// Admin Routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('cafes', App\Http\Controllers\Admin\CafeController::class);
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('subcategories', App\Http\Controllers\Admin\SubcategoryController::class);
    Route::resource('weights', WeightController::class);
    Route::get('/contact-messages', [ContactController::class, 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{contactMessage}', [ContactController::class, 'show'])->name('contact-messages.show');
    Route::delete('/contact-messages/{contactMessage}', [ContactController::class, 'destroy'])->name('contact-messages.destroy');
    Route::delete('cafes/delete-image/{id}', [App\Http\Controllers\Admin\CafeController::class, 'deleteImage'])->name('cafes.delete-image');
    Route::post('cafes/update-image-order', [App\Http\Controllers\Admin\CafeController::class, 'updateImageOrder'])->name('cafes.update-image-order');
});

Route::get('/cafe/{cafe}', [App\Http\Controllers\Admin\CafeController::class, 'show'])->name('cafe.show');

// Contact Routes
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
