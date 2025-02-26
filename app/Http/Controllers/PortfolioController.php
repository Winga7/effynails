<?php

namespace App\Http\Controllers\Admin;

use App\Models\Portfolio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // Max 5MB
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_featured' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::uuid() . '.webp';

            // Optimisation et conversion en WebP
            $img = Image::make($image)
                ->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode('webp', 80);

            // Ajout du watermark
            $img->text('© EFFYNAILS', $img->width() - 20, $img->height() - 20, function ($font) {
                $font->file(public_path('fonts/your-font.ttf'));
                $font->size(24);
                $font->color('#ffffff');
                $font->align('right');
                $font->valign('bottom');
            });

            // Sauvegarde
            Storage::put('public/portfolio/' . $filename, $img->stream());

            Portfolio::create([
                'title' => $request->title,
                'image_path' => 'portfolio/' . $filename,
                'description' => $request->description,
                'is_featured' => $request->is_featured ?? false,
            ]);
        }

        return redirect()->back()->with('success', 'Image ajoutée avec succès');
    }
}
