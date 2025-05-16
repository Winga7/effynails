<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * 🧪 TestCase
 *
 * Classe de base pour tous les tests de l'application
 * Fournit les fonctionnalités de test de Laravel
 * Permet la configuration commune pour tous les tests
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * 📦 Configuration de base pour les tests
     *
     * Méthode appelée avant chaque test
     * Idéal pour la configuration commune des tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Configuration commune pour tous les tests
    }

    /**
     * 🧹 Nettoyage après les tests
     *
     * Méthode appelée après chaque test
     * Idéal pour le nettoyage des ressources
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        // Nettoyage après chaque test
    }
}
