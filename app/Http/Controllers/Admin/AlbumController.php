<?php

namespace App\Http\Controllers\Admin;

use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AlbumController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
            'order' => 'integer|nullable',
        ]);

        // Ajout d'un ordre par défaut si non spécifié
        if (!isset($validated['order'])) {
            $validated['order'] = Album::max('order') + 1;
        }

        Album::create($validated);

        return redirect()->back()->with('success', 'Album créé avec succès');
    }

    // Nouvelle méthode pour la mise à jour
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

    // Nouvelle méthode pour la suppression
    public function destroy(Album $album)
    {
        // Vous pouvez ajouter une logique pour supprimer les images associées
        // ou laisser la suppression en cascade gérer cela via votre migration

        $album->delete();

        return redirect()->back()->with('success', 'Album supprimé avec succès');
    }
}
