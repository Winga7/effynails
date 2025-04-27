<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Album;
use App\Http\Controllers\Admin\AlbumController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\ContactController;

// Routes publiques (accessibles sans connexion)
Route::get('/', function () {
    // Récupération des dernières images du portfolio
    $latestPortfolio = \App\Models\Portfolio::orderBy('created_at', 'desc')
        ->take(9)
        ->get();
        
    return Inertia::render('Welcome', [
        'latestPortfolio' => $latestPortfolio
    ]);
})->name('home');

Route::get('/presentation', function () {
    return Inertia::render('Presentation');
})->name('presentation');

Route::get('/tarifs', [TarifController::class, 'publicPage'])->name('tarifs');

Route::get('/portfolio', function () {
    return Inertia::render('Portfolio');
})->name('portfolio');

// Routes pour la page de contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact-send', [ContactController::class, 'send'])->name('contact.send');

Route::get('/reservation', function () {
    return Inertia::render('Reservation');
})->name('reservation');

// Routes pour les pages légales
Route::get('/mentions-legales', function () {
    return Inertia::render('MentionsLegales');
})->name('mentions-legales');

Route::get('/plan-du-site', function () {
    return Inertia::render('PlanDuSite');
})->name('plan-du-site');

Route::get('/politique-confidentialite', function () {
    $policyFile = resource_path('markdown/policy.md');
    $policy = file_exists($policyFile)
        ? file_get_contents($policyFile)
        : 'La politique de confidentialité n\'a pas encore été rédigée.';
    return Inertia::render('PolitiqueConfidentialite', [
        'policy' => $policy,
    ]);
})->name('politique-confidentialite');

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

        // Routes d'administration des tarifs
        Route::get('/tarifs', [TarifController::class, 'adminPage'])->name('admin.tarifs');
    });
});

// API pour les tarifs
Route::prefix('api')->group(function () {
    Route::get('/tarifs', [TarifController::class, 'index']);
    Route::post('/tarifs', [TarifController::class, 'store'])->middleware(['auth:sanctum']);
    Route::get('/tarifs/{tarif}', [TarifController::class, 'show']);
    Route::put('/tarifs/{tarif}', [TarifController::class, 'update'])->middleware(['auth:sanctum']);
    Route::delete('/tarifs/{tarif}', [TarifController::class, 'destroy'])->middleware(['auth:sanctum']);
});
