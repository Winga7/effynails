<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * 🔄 HandleInertiaRequests
 *
 * Middleware pour la gestion des requêtes Inertia.js
 * Assure la cohérence entre le backend Laravel et le frontend Vue.js
 * Gère le partage des données et la version des assets
 */
class HandleInertiaRequests extends Middleware
{
    /**
     * 📄 Template racine chargé lors de la première visite
     *
     * Définit le template de base pour toutes les pages Inertia
     * Utilisé comme point d'entrée pour l'application
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * 🔢 Détermine la version actuelle des assets
     *
     * Utilisé pour l'invalidation du cache des assets
     * Permet de forcer le rechargement des ressources lors des mises à jour
     *
     * @see https://inertiajs.com/asset-versioning
     * @param Request $request La requête HTTP
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * 📦 Définit les props partagées par défaut
     *
     * Données disponibles dans toutes les pages de l'application
     * Peut inclure des informations globales comme l'utilisateur connecté
     *
     * @see https://inertiajs.com/shared-data
     * @param Request $request La requête HTTP
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            //
        ]);
    }
}
