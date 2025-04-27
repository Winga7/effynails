<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Presentation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PresentationController extends Controller
{
  public function edit()
  {
    $presentation = Presentation::first() ?? new Presentation();

    return Inertia::render('Admin/PresentationAdmin', [
      'presentation' => $presentation
    ]);
  }

  public function update(Request $request)
  {
    $request->validate([
      'content' => 'nullable|string',
      'about_title' => 'nullable|string',
      'about_content' => 'nullable|string',
      'experience_title' => 'nullable|string',
      'experience_content' => 'nullable|string',
    ]);

    $presentation = Presentation::first() ?? new Presentation();
    $presentation->fill($request->all());
    $presentation->save();

    return redirect()->back()->with('success', 'Présentation mise à jour avec succès');
  }
}
