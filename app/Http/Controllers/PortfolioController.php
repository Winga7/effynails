<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Inertia\Inertia;

/**
 * 🖼️ PortfolioController
 *
 * Gère l'affichage du portfolio et des albums
 * Permet la visualisation des réalisations par catégorie
 * Assure la navigation entre les albums et leurs contenus
 */
class PortfolioController extends Controller
{
    /**
     * 📱 Page principale du portfolio
     *
     * Affiche la liste des albums triés par ordre
     * Charge les portfolios associés à chaque album
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $albums = Album::with('portfolios')
            ->orderBy('order')
            ->get();

        return Inertia::render('Portfolio', [
            'albums' => $albums
        ]);
    }

    /**
     * 🎯 Affichage d'un album spécifique
     *
     * Charge et affiche les détails d'un album
     * Inclut tous les portfolios associés
     *
     * @param Album $album L'album à afficher
     * @return \Inertia\Response
     */
    public function show(Album $album)
    {
        $album->load('portfolios');

        return Inertia::render('Portfolio/Show', [
            'album' => $album
        ]);
    }
}
