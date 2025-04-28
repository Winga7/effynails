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

class PortfolioController extends Controller
{
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

                // Créer les dossiers s'ils n'existent pas
                if (!file_exists(storage_path('app/public/portfolio'))) {
                    mkdir(storage_path('app/public/portfolio'), 0755, true);
                }

                // Initialiser le gestionnaire d'image
                $manager = new ImageManager(new Driver());
                Log::info('ImageManager initialisé');

                // Traiter l'image
                $img = $manager->read($image->getRealPath())
                    ->resize(1280, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->toWebp(90);

                Log::info('Image redimensionnée et convertie en WebP');

                // Sauvegarder directement dans le dossier storage
                $savePath = storage_path('app/public/portfolio/' . $filename);
                file_put_contents($savePath, $img->toString());

                Log::info('Image sauvegardée dans : ' . $savePath);
                Log::info('Le fichier existe : ' . (file_exists($savePath) ? 'Oui' : 'Non'));

                // Créer l'entrée dans la base de données
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
}
