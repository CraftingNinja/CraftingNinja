<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CraftController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HuntingController;
use App\Http\Controllers\LevesController;
use App\Http\Controllers\ListsController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [WelcomeController::class, 'index'])->name('home');

// TODO 1 what is dashboard?
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware([
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // You can only create lists if you're logged in
    Route::match(['get', 'post'], 'lists/create-from-cart', [ListsController::class, 'createFromCart'])->name('lists.create-from-cart');
    Route::resource('lists', ListsController::class)->except('create', 'restore');
});

Route::get('craft/list', [CraftController::class, 'fromActiveList'])->name('craft.active');
Route::get('craft/list/{list}', [CraftController::class, 'fromList'])->name('craft.list');

Route::resource('library', LibraryController::class)->only('index');
Route::resource('cart', CartController::class)->only('index', 'store');
Route::resource('lists', ListsController::class)->only('index', 'show');

Route::resource('equipment', EquipmentController::class)->only('index');
Route::resource('food', FoodController::class)->only('index');
Route::resource('leves', LevesController::class)->only('index');
Route::resource('hunting', HuntingController::class)->only('index');
