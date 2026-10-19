<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — BetulinAja Frontend Prototype
|--------------------------------------------------------------------------
| Seluruh route ini khusus menangani antarmuka frontend (Blade templates)
| tanpa dependensi database MySQL atau API backend sesuai spesifikasi tahap 1.
|
*/

// =========================================================================
// 1. Customer (Pelanggan) Routes
// =========================================================================
Route::get('/', function () {
    return view('customer.home');
})->name('home');

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/services', function () {
        return view('customer.services');
    })->name('services');

    Route::get('/booking', function () {
        return view('customer.booking');
    })->name('booking');

    Route::get('/location', function () {
        return view('customer.location');
    })->name('location');

    Route::get('/estimate', function () {
        return view('customer.estimate');
    })->name('estimate');

    Route::get('/technicians', function () {
        return view('customer.technicians');
    })->name('technicians');

    Route::get('/confirmation', function () {
        return view('customer.confirmation');
    })->name('confirmation');

    Route::get('/tracking', function () {
        return view('customer.tracking');
    })->name('tracking');

    Route::get('/profile', function () {
        return view('customer.profile');
    })->name('profile');

    Route::get('/order-history', function () {
        return view('customer.order-history');
    })->name('order-history');
});

// =========================================================================
// 2. Partner (Mitra Teknisi) Routes
// =========================================================================
Route::prefix('partner')->name('partner.')->group(function () {
    Route::get('/login', function () {
        return view('partner.login');
    })->name('login');

    Route::get('/dashboard', function () {
        return view('partner.dashboard');
    })->name('dashboard');

    Route::get('/orders', function () {
        return view('partner.orders');
    })->name('orders');

    Route::get('/order-detail', function () {
        return view('partner.order-detail');
    })->name('order-detail');

    Route::get('/active-job', function () {
        return view('partner.active-job');
    })->name('active-job');

    Route::get('/earnings', function () {
        return view('partner.earnings');
    })->name('earnings');

    Route::get('/profile', function () {
        return view('partner.profile');
    })->name('profile');
});

// =========================================================================
// 3. Admin Portal Routes
// =========================================================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/orders', function () {
        return view('admin.orders');
    })->name('orders');

    Route::get('/customers', function () {
        return view('admin.customers');
    })->name('customers');

    Route::get('/technicians', function () {
        return view('admin.technicians');
    })->name('technicians');

    Route::get('/services', function () {
        return view('admin.services');
    })->name('services');

    Route::get('/pricing', function () {
        return view('admin.pricing');
    })->name('pricing');

    Route::get('/reviews', function () {
        return view('admin.reviews');
    })->name('reviews');

    Route::get('/reports', function () {
        return view('admin.reports');
    })->name('reports');

    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});
