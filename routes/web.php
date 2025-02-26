<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\PortfolioController;
use App\Models\Album;

// Routes publiques (accessibles sans connexion)
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/presentation', function () {
    return Inertia::render('Presentation');
})->name('presentation');

Route::get('/tarifs', function () {
    return Inertia::render('Tarifs');
})->name('tarifs');

Route::get('/portfolio', function () {
    return Inertia::render('Portfolio');
})->name('portfolio');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::get('/reservation', function () {
    return Inertia::render('Reservation');
})->name('reservation');

// Routes administrateur (nécessitent une connexion)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Routes pour la gestion du contenu
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/tarifsadmin', function () {
            return Inertia::render('Admin/TarifsAdmin');
        })->name('tarifsadmin');

        Route::get('/portfolioadmin', function () {
            return Inertia::render('Admin/PortfolioAdmin', [
                'albums' => Album::with('portfolios')->get(),
            ]);
        })->name('portfolioadmin');

        // Routes pour les albums et portfolios
        Route::post('/albums', [AlbumController::class, 'store'])->name('albums.store');
        Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
    });
});
