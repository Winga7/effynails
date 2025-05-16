<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * 🚀 AppServiceProvider
 *
 * Fournisseur de services principal de l'application
 * Gère l'enregistrement et le démarrage des services
 * Point central pour la configuration de l'application
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * 📝 Enregistrement des services de l'application
     *
     * Méthode appelée lors de l'enregistrement des services
     * Idéal pour lier des interfaces à leurs implémentations
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * 🚀 Démarrage des services de l'application
     *
     * Méthode appelée après l'enregistrement de tous les services
     * Idéal pour la configuration des services enregistrés
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
