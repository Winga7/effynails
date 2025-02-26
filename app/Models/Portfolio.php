<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'description',
        'is_featured',
        'order',
        'album_id'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    // Accesseur pour obtenir l'URL complète de l'image
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
