<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * 💰 TarifController
 *
 * Gère la gestion des tarifs et services
 * Permet l'administration et l'affichage public des tarifs
 * Assure la validation et la persistance des données
 */
class TarifController extends Controller
{
    /**
     * 📋 Liste des tarifs
     *
     * Récupère et retourne tous les tarifs disponibles
     * Format JSON pour l'API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tarifs = Tarif::all();
        return response()->json($tarifs);
    }

    /**
     * ➕ Création d'un nouveau tarif
     *
     * Valide et enregistre un nouveau tarif
     * Vérifie les contraintes de données
     *
     * @param Request $request Données du tarif
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $tarif = Tarif::create($validated);
        return response()->json($tarif, 201);
    }

    /**
     * 👁️ Affichage d'un tarif
     *
     * Récupère et affiche les détails d'un tarif spécifique
     *
     * @param Tarif $tarif Le tarif à afficher
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Tarif $tarif)
    {
        return response()->json($tarif);
    }

    /**
     * ✏️ Mise à jour d'un tarif
     *
     * Modifie les informations d'un tarif existant
     * Valide les nouvelles données
     *
     * @param Request $request Nouvelles données
     * @param Tarif $tarif Tarif à modifier
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Tarif $tarif)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $tarif->update($validated);
        return response()->json($tarif);
    }

    /**
     * 🗑️ Suppression d'un tarif
     *
     * Supprime définitivement un tarif de la base de données
     *
     * @param Tarif $tarif Tarif à supprimer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Tarif $tarif)
    {
        $tarif->delete();
        return response()->json(null, 204);
    }

    /**
     * 👨‍💼 Page d'administration des tarifs
     *
     * Interface d'administration pour la gestion des tarifs
     *
     * @return \Inertia\Response
     */
    public function adminPage()
    {
        return Inertia::render('Admin/TarifsAdmin');
    }

    /**
     * 👥 Page publique des tarifs
     *
     * Affichage public des tarifs pour les clients
     *
     * @return \Inertia\Response
     */
    public function publicPage()
    {
        return Inertia::render('Tarifs');
    }
}
