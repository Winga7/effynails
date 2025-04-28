<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Inertia\Inertia;

class PortfolioController extends Controller
{
  public function index()
  {
    $albums = Album::with('portfolios')
      ->orderBy('order')
      ->get();

    return Inertia::render('Portfolio', [
      'albums' => $albums
    ]);
  }

  public function show(Album $album)
  {
    $album->load('portfolios');

    return Inertia::render('Portfolio/Show', [
      'album' => $album
    ]);
  }
}
