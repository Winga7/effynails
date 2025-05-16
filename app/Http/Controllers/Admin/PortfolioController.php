<?php

namespace App\Http\Controllers\Admin;

use App\Models\Portfolio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * 📸 PortfolioController
 *
 * Gère toutes les opérations CRUD liées aux photos du portfolio
 * Ce contrôleur permet de créer, mettre à jour et supprimer des photos
 * avec optimisation automatique (redimensionnement et conversion WebP)
 */
class PortfolioController extends Controller
{
    /**
     * 📝 Création d'une nouvelle photo dans le portfolio
     *
     * @param Request $request Les données de la photo (image, titre, description, etc.)
     * @return \Illuminate\Http\RedirectResponse
     *
     * Note: L'image est automatiquement redimensionnée (1280px de largeur max)
     * et convertie au format WebP pour optimiser les performances
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // Max 5MB
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'boolean',
            'album_id' => 'required|exists:albums,id'
        ]);

        try {
            if ($request->hasFile('image')) {
                Log::info('Début du traitement de l\'image');

                $image = $request->file('image');
                $filename = Str::uuid() . '.webp';

                Log::info('Nom du fichier généré : ' . $filename);

                // 📁 Créer les dossiers s'ils n'existent pas
                if (!file_exists(storage_path('app/public/portfolio'))) {
                    mkdir(storage_path('app/public/portfolio'), 0755, true);
                }

                // 🖼️ Initialiser le gestionnaire d'image
                $manager = new ImageManager(new Driver());
                Log::info('ImageManager initialisé');

                // 🔄 Traiter l'image (redimensionnement + conversion WebP)
                $img = $manager->read($image->getRealPath())
                    ->resize(1280, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->toWebp(90);

                Log::info('Image redimensionnée et convertie en WebP');

                // 💾 Sauvegarder l'image
                $savePath = storage_path('app/public/portfolio/' . $filename);
                file_put_contents($savePath, $img->toString());

                Log::info('Image sauvegardée dans : ' . $savePath);
                Log::info('Le fichier existe : ' . (file_exists($savePath) ? 'Oui' : 'Non'));

                // 📊 Créer l'entrée dans la base de données
                $portfolio = Portfolio::create([
                    'title' => $request->title,
                    'image_path' => 'portfolio/' . $filename,
                    'description' => $request->description,
                    'is_featured' => $request->is_featured ?? false,
                    'album_id' => $request->album_id
                ]);

                Log::info('Entrée créée dans la base de données avec l\'ID : ' . $portfolio->id);

                return redirect()->back()->with('success', 'Image ajoutée avec succès');
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upload : ' . $e->getMessage());
            Log::error('Stack trace : ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'upload de l\'image');
        }
    }

    /**
     * 🔄 Mise à jour d'une photo existante du portfolio
     *
     * @param Request $request Les nouvelles données de la photo
     * @param Portfolio $portfolio La photo à mettre à jour
     * @return \Illuminate\Http\RedirectResponse
     *
     * Note: Si une nouvelle image est fournie, l'ancienne est automatiquement supprimée
     * La nouvelle image est optimisée selon les mêmes critères que lors de la création
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $request->validate([
            'image' => 'nullable|image|max:5120', // Max 5MB
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'boolean'
        ]);

        try {
            Log::info("Mise à jour du portfolio ID: {$portfolio->id}", [
                'title' => $request->title,
                'has_image' => $request->hasFile('image')
            ]);

            // 📝 Mise à jour des données textuelles
            $portfolio->title = $request->title;
            $portfolio->description = $request->description;
            $portfolio->is_featured = $request->is_featured ?? false;

            // 🖼️ Traitement de l'image si elle est fournie
            if ($request->hasFile('image')) {
                Log::info("Mise à jour de l'image pour le portfolio ID: {$portfolio->id}");

                // 🗑️ Supprimer l'ancienne image si elle existe
                if ($portfolio->image_path) {
                    $oldImagePath = storage_path('app/public/' . $portfolio->image_path);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                        Log::info("Ancienne image supprimée: {$oldImagePath}");
                    }
                }

                $image = $request->file('image');
                $filename = Str::uuid() . '.webp';

                // 📁 Créer les dossiers s'ils n'existent pas
                if (!file_exists(storage_path('app/public/portfolio'))) {
                    mkdir(storage_path('app/public/portfolio'), 0755, true);
                }

                // 🖼️ Initialiser le gestionnaire d'image
                $manager = new ImageManager(new Driver());

                // 🔄 Traiter la nouvelle image
                $img = $manager->read($image->getRealPath())
                    ->resize(1280, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->toWebp(90);

                // 💾 Sauvegarder la nouvelle image
                $savePath = storage_path('app/public/portfolio/' . $filename);
                file_put_contents($savePath, $img->toString());

                // 📊 Mettre à jour le chemin de l'image
                $portfolio->image_path = 'portfolio/' . $filename;
                Log::info("Nouvelle image sauvegardée: {$filename}");
            }

            $portfolio->save();
            Log::info("Portfolio mis à jour avec succès, ID: {$portfolio->id}");

            return redirect()->back()->with('success', 'Photo mise à jour avec succès');
        } catch (\Exception $e) {
            Log::error("Erreur lors de la mise à jour du portfolio ID {$portfolio->id}: {$e->getMessage()}", [
                'portfolio_id' => $portfolio->id,
                'exception' => get_class($e)
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de la photo');
        }
    }

    /**
     * 🗑️ Suppression d'une photo du portfolio
     *
     * @param Portfolio $portfolio La photo à supprimer
     * @return \Illuminate\Http\RedirectResponse
     *
     * Note: Supprime à la fois l'enregistrement en base de données
     * et le fichier image associé du système de fichiers
     */
    public function destroy(Portfolio $portfolio)
    {
        try {
            Log::info("Suppression du portfolio ID: {$portfolio->id}", [
                'portfolio_title' => $portfolio->title,
                'image_path' => $portfolio->image_path
            ]);

            // 🗑️ Supprimer l'image associée
            if ($portfolio->image_path) {
                $imagePath = storage_path('app/public/' . $portfolio->image_path);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                    Log::info("Image supprimée: {$imagePath}");
                } else {
                    Log::warning("Image introuvable lors de la suppression: {$imagePath}");
                }
            }

            // 📊 Supprimer l'enregistrement de la base de données
            $portfolio->delete();
            Log::info("Portfolio supprimé avec succès");

            return redirect()->back()->with('success', 'Photo supprimée avec succès');
        } catch (\Exception $e) {
            Log::error("Erreur lors de la suppression du portfolio ID {$portfolio->id}: {$e->getMessage()}", [
                'portfolio_id' => $portfolio->id,
                'exception' => get_class($e)
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la suppression de la photo');
        }
    }
}
