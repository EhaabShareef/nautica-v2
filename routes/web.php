<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Configuration\Index as ConfigurationIndex;
use App\Livewire\Admin\Configuration\Roles\Index as RolesIndex;
use App\Livewire\Admin\Management\Clients\Index as ClientsIndex;
use App\Livewire\Admin\Management\Vessels\Index as VesselsIndex;
use App\Livewire\Admin\Bookings\NewReservation;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Client\Dashboard as ClientDashboard;
use App\Livewire\LandingPage;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\Configuration\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingPage::class)->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Logout route
Route::post('/logout', [LogoutController::class, '__invoke'])->middleware('auth')->name('logout');

// Admin routes group
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/configuration', ConfigurationIndex::class)->name('configuration');
    Route::get('/configuration/roles', RolesIndex::class)->name('configuration.roles');
    Route::get('/configuration/settings', [SettingsController::class, 'index'])->name('configuration.settings');

    // Management routes
    Route::prefix('management')->name('management.')->group(function () {
        Route::get('/clients', ClientsIndex::class)->name('clients');
        Route::get('/vessels', VesselsIndex::class)->name('vessels');
    });

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/new', NewReservation::class)->name('new');
    });
});

// Client routes group  
Route::middleware(['auth', 'client'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', ClientDashboard::class)->name('dashboard');
});

// Agent routes group (for future expansion)
Route::middleware(['auth', 'agent'])->prefix('agent')->name('agent.')->group(function () {
    // Future agent routes here
});
