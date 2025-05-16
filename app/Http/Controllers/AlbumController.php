<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * 🎨 AlbumController
 *
 * Gère toutes les opérations CRUD liées aux albums de photos
 * Permet la création, modification et suppression des albums
 * Assure la gestion de l'ordre d'affichage et des albums mis en avant
 */
class AlbumController extends Controller
{
    /**
     * 📝 Création d'un nouvel album
     *
     * Valide et enregistre un nouvel album avec ses métadonnées
     * Gère le titre, la description, l'ordre et le statut "mis en avant"
     *
     * @param Request $request Les données validées de l'album
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
            'order' => 'integer',
        ]);

        Album::create($validated);

        return redirect()->back();
    }

    /**
     * 🔄 Mise à jour d'un album existant
     *
     * Modifie les informations d'un album existant
     * Permet de changer le titre, la description, l'ordre et le statut
     * Retourne un message de succès après la mise à jour
     *
     * @param Request $request Les nouvelles données de l'album
     * @param Album $album L'album à mettre à jour
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
            'order' => 'integer|nullable',
        ]);

        $album->update($validated);

        return redirect()->back()->with('success', 'Album mis à jour avec succès');
    }

    /**
     * 🗑️ Suppression d'un album
     *
     * Supprime définitivement un album et ses ressources associées
     * La suppression des images est gérée automatiquement via les relations
     * Retourne un message de confirmation après la suppression
     *
     * @param Album $album L'album à supprimer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Album $album)
    {
        $album->delete();

        return redirect()->back()->with('success', 'Album supprimé avec succès');
    }
}
