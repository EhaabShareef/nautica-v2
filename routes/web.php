<?php

use App\Http\Livewire\Admin\Dashboard as AdminDashboard;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Client\Dashboard as ClientDashboard;
use App\Http\Livewire\LandingPage;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPage::class);

Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/register', Register::class)->middleware('guest');

Route::middleware('auth')->group(function () {
    Route::get('/client/dashboard', ClientDashboard::class)->middleware('role:client');
    Route::get('/admin/dashboard', AdminDashboard::class)->middleware('role:admin');
});
