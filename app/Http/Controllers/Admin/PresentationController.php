<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presentation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

/**
 * 📝 PresentationController
 *
 * Gère la présentation de l'entreprise et les informations "À propos"
 * Permet la modification du contenu de présentation et des sections
 * Assure la persistance des informations de l'entreprise
 */
class PresentationController extends Controller
{
    /**
     * ✏️ Édition de la présentation
     *
     * Affiche le formulaire d'édition de la présentation
     * Récupère les données existantes ou crée une nouvelle présentation
     *
     * @return \Inertia\Response
     */
    public function edit()
    {
        $presentation = Presentation::first() ?? new Presentation();

        return Inertia::render('Admin/PresentationAdmin', [
            'presentation' => $presentation
        ]);
    }

    /**
     * 💾 Mise à jour de la présentation
     *
     * Valide et enregistre les modifications de la présentation
     * Gère le contenu principal et les sections "À propos" et "Expérience"
     * Journalise les modifications pour le suivi
     *
     * @param Request $request Données de la présentation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // 📋 Journalisation des données reçues
        Log::info('Présentation update', $request->all());

        // ✅ Validation des données
        $request->validate([
            'content' => 'nullable|string',
            'about_title' => 'nullable|string',
            'about_content' => 'nullable|string',
            'experience_title' => 'nullable|string',
            'experience_content' => 'nullable|string',
        ]);

        // 💾 Sauvegarde des modifications
        $presentation = Presentation::first() ?? new Presentation();
        $presentation->fill($request->all());
        $presentation->save();

        return redirect()->back()->with('success', 'Présentation mise à jour avec succès');
    }
}
