<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TarifController extends Controller
{
    /**
     * Afficher tous les tarifs.
     */
    public function index()
    {
        $tarifs = Tarif::all();
        return response()->json($tarifs);
    }

    /**
     * Enregistrer un nouveau tarif.
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
     * Afficher un tarif spécifique.
     */
    public function show(Tarif $tarif)
    {
        return response()->json($tarif);
    }

    /**
     * Mettre à jour un tarif existant.
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
     * Supprimer un tarif.
     */
    public function destroy(Tarif $tarif)
    {
        $tarif->delete();
        return response()->json(null, 204);
    }

    /**
     * Afficher la page d'administration des tarifs.
     */
    public function adminPage()
    {
        return Inertia::render('Admin/TarifsAdmin');
    }

    /**
     * Afficher la page publique des tarifs.
     */
    public function publicPage()
    {
        return Inertia::render('Tarifs');
    }
} 