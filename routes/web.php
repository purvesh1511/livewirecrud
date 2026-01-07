<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\ProductCrud;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('product', 'product')
    ->middleware(['auth', 'verified'])
    ->name('product');

require __DIR__.'/auth.php';
