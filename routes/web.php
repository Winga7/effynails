<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Album;
use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\PortfolioController;

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
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Routes pour la gestion du contenu administratif
    Route::prefix('admin')->name('admin.')->group(function () {
        // Gestion des tarifs
        Route::get('/tarifsadmin', function () {
            return Inertia::render('Admin/TarifsAdmin');
        })->name('tarifsadmin');

        // Gestion du portfolio - PAGE PRINCIPALE
        Route::get('/portfolioadmin', function () {
            return Inertia::render('Admin/PortfolioAdmin', [
                'albums' => Album::with('portfolios')->get(),
            ]);
        })->name('portfolioadmin');

        // Routes API pour les albums
        Route::post('/albums', [AlbumController::class, 'store'])->name('albums.store');
        Route::get('/albums', [AlbumController::class, 'index'])->name('albums.index');
        Route::put('/albums/{album}', [AlbumController::class, 'update'])->name('albums.update');
        Route::delete('/albums/{album}', [AlbumController::class, 'destroy'])->name('albums.destroy');

        // Routes API pour les portfolios
        Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
    });
});
